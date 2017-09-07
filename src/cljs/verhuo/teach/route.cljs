(ns verihuo.teach.route
  (:require [secretary.core :as secretary :include-macros true]
            [reagent.session :as session]
            [ajax.core :as a]
            [verihuo.teach.pages.student :as ps]
            [verihuo.teach.pages.nav :as pa]
            [verihuo.teach.authorize :as auth]
            [goog.events :as events]
            [goog.history.EventType :as HistoryEventType])
  (:import goog.History))

(defn- reorder [students]
  (let [results students]
    (into (sorted-map-by (fn [key1 key2]
                           (compare [(get-in results [key2 :user_name]) key2]
                                    [(get-in results [key1 :user_name]) key1])))
          results)))

(defn- gen-avg [x]
  (assoc x :scores
         (->> (:scores x)
              (map #(assoc % :avg (/ (->> (select-keys
                                           % [:c1 :c2 :c3 :c4 :c5])
                                          (vals)
                                          (apply +)) 5)))
              (into []))))

(defn- reorder-score [student]
  (let [s student
        scores (into [] (reverse (sort-by :at (:scores s))))]
    (assoc s :scores scores)))

;; -------------------------
;; Routes
(secretary/set-config! :prefix "#")

(secretary/defroute dashboard "/" []
  (session/put! :page :dashboard))

(secretary/defroute student "/student" []
  (a/GET "/teach/rest/student"
         {:response-format :json
          :keywords? true
          :handler
          #(->> (:students %)
                (map (juxt :id identity))
                (into {})
                (reorder)
                (reset! ps/students))}) 
  (session/put! :page :student))

(secretary/defroute student-edit
  "/student/:id/edit" [id]
  (do
    (swap! ps/edit-score assoc :show false)
    (a/GET (str "/teach/rest/class/" )
           {:response-format :json
            :keywords? true
            :handler
            #(->> (:classes %)
                  (map (juxt :id identity))
                  (into {})
                  (reset! ps/a-classes) )})
    (a/GET (str "/teach/rest/student/" id "/edit")
           {:response-format :json
            :keywords? true
            :handler
            #(->> (:student %)
                  (reorder-score)
                  (gen-avg)
                  (reset! ps/student))}) 
    (session/put! :page :student-edit)))

(secretary/defroute edit-advise
  "/student/:id/edit-advise" [id]
  (do (reset! ps/student {:id id})
      (a/GET (str "/teach/rest/student/" id "/advise")
             {:response-format :json
              :keywords? true
              :handler
              #(->> % (reset! ps/advises))})
      (session/put! :page :edit-advise)))

(secretary/defroute final-grade
  "/student/:id/finalgrade" [id]
  (do (reset! ps/student {:id id})
      (a/GET (str "/teach/rest/student/" id)
             {:response-format :json
              :keywords? true
              :handler
              #(->> % (reset! ps/student))})
      (a/GET (str "/teach/rest/student/" id "/fgrade")
             {:response-format :json
              :keywords? true
              :handler
              #(->> % (reset! ps/fgrade))})
      (session/put! :page :student-finalgrade)))

;; -------------------------
;; History
;; must be called after routes have been defined
(defn hook-browser-navigation! []
  (doto (History.)
    (events/listen
     HistoryEventType/NAVIGATE
     (fn [event]
       (do (auth/login?)
           (secretary/dispatch! (.-token event)))))
    (.setEnabled true)))

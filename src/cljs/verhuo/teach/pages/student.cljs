(ns verihuo.teach.pages.student
  (:require [reagent.core :as r]
            [ajax.core :as a]
            [dommy.core :as dommy :refer-macros [sel sel1]]
            [secretary.core :as secretary]
            [reagent.session :as session]
            [verihuo.teach.pages.nav :as nav]
            [verihuo.teach.score :refer [s->g g->s]]))



(defonce students (r/atom (sorted-map-by >)))

(defonce student (r/atom {:user_name "b"}))

(defonce show-score-all (r/atom false))
(defonce edit-score (r/atom {}))

(defonce a-classes (r/atom (sorted-map)))

(defonce advises (r/atom {}))

(defonce fgrade (r/atom {}))

(defn rows [data]
  [:tbody
   (doall
    (for [[key item] data]
      ^{:key key}
      [:tr
       [:td (:user_name item)]
       [:td (:phone item)]
       [:td (:email item)]
       [:td.ml-5
        [:a.btn.btn-sm.btn-link.btn-danger {:href (str "/teach/app/#/student/"  key "/edit")} "Edit Score"]
        [:a.btn.btn-sm.btn-link.btn-danger {:href (str "/teach/app/#/student/"  key "/edit-advise")} "Advise"]
        (cond (= 2 (session/get-in [:auth :level]))
              [:a.btn.btn-sm.btn-link.btn-danger {:href (str "/teach/app/#/student/"  key "/finalgrade")} "Final Grade"])
        ]]))])

(defn main []
  [:div.container
   [:div.row>div.col-12
    [:table.table.table-bordered.table-striped
     {:id "student-table"}
     [:thead.table-inverse
      [:tr
       [:th "Name"]
       [:th "Phone"]
       [:th "Email"]
       [:th "action"]]]
     [rows @students]]]])

(defn- score-box [score]
  [:div.border.rounded.align-middle.m-2.score-box
   {:on-click #(reset! edit-score (assoc score :show true))
    :class
    (if (= (:id @edit-score) (:id score)) "score-box-active" "")}
   [:span
    (:at score)]
   [:br]
   [:span "You get the "]
   [:span.score
    (s->g (:avg score))]
   [:span " !"]])

(defn- score-boxes [scores]
  [:div.row>div.col-12.score-boxes
   {:class (if @show-score-all "score-boxes-all" "")}
    (for [score scores]
      ^{:key  score}
      [score-box score])])

(defn- btns []
  [:div.row>div.col-12
   [:a.btn.btn-info.m-2
    {:href "/teach/app/#/student"}
    "my students"]
   [:button.btn.btn-info.m-2
     {:on-click #(reset! show-score-all (not @show-score-all))}
    (if (= true @show-score-all) "hide scores" "all scores")]
   [:button.btn.btn-info.m-2
    {:on-click #(reset! edit-score {:id 0 :show true})}
    "Add class's score"]
   ])

(defn- on-click []
  (let [x (fn []
            (js/setTimeout #(swap! edit-score dissoc :alert) 5000))
        y #(swap! edit-score assoc :alert %)
        id (:id @edit-score)
        url "/teach/rest/student/"
        p {:response-format :json
           :format :json
           :headers {:X-CSRF-TOKEN
                     (dommy/attr (sel1 "meta[name=\"csrf-token\"") "content")}
           :keywords? true
           :params {:score (assoc
                            (select-keys @edit-score
                                         [:id :class_id :at 
                                          :c1 :c2 :c3 :c4 :c5
                                          :a1 :a2 :a3 :a4 :a5])
                            :customer_id (:id @student))}
           :handler
           #(set! js/window.location.href "/teach/app/#/student" )}
        z (fn []
            (cond (zero? id) (a/POST url p)
                  :else (a/PUT (str url id) p)))]
    (cond (nil? (:class_id @edit-score)) (y "Please select a class!")
          (nil? (:at @edit-score)) (y "Please pickup a datetime !")
          (nil? (:c1 @edit-score)) (y "Please select a score for c1!")
          (nil? (:c2 @edit-score)) (y "Please select a score for c2!")
          (nil? (:c3 @edit-score)) (y "Please select a score for c3!")
          (nil? (:c4 @edit-score)) (y "Please select a score for c4!")
          (nil? (:c5 @edit-score)) (y "Please select a score for c5!")
          (nil? (:a1 @edit-score)) (y "Please select a score for a1!")
          (nil? (:a2 @edit-score)) (y "Please select a score for a2!")
          (nil? (:a3 @edit-score)) (y "Please select a score for a3!")
          (nil? (:a4 @edit-score)) (y "Please select a score for a4!")
          (nil? (:a5 @edit-score)) (y "Please select a score for a5!")
          :else (z)))
  )

(defn- confirm-btn []
  [:div.row>div.col-12
   [:p {:style {:color "red"}} (@edit-score :alert)]
   [:button.btn.btn-info.m-2
    {:on-click #'on-click}
    "Confirm"]])

(defn- cb-classes []
  [:div.row>div.col-12
   [:h6 "Seclect Class:"]
   (doall
    (for [[key class] @a-classes]
      ^{:key key}
      [:label.custom-control.custom-checkbox.mb-2
       [:input.custom-control-input
        {:type "checkbox"
         :checked (if (= (:class_id @edit-score) (:id class))
                    "checked"
                    "")
         :onChange #(swap! edit-score assoc :class_id (:id class))}]
       [:span.custom-control-indicator ""]
       [:span.custom-control-description (:name class)]]))])

(defn- pickdatetime []
  [:div.row>div.col-12
   [:h6 "Pick Time:"]
   [:div.row>div.col-3
    [:> js/Datetime
     {:dateFormat "YYYY-MM-DD"
      :timeFormat "HH:mm"
      :value (cond (:at @edit-score) (:at @edit-score)
                   :else "2017-01-01 00:00")
      :onChange #(swap! edit-score assoc
                        :at (.format % "YYYY-MM-DD HH:mm"))}]]])

(defn- score1 []
  [:div.row>div.col-12
   [:h6 "score"]
   (doall 

    (for [[k v] {:c1 {:name "Listening"},
                 :c2 {:name "Speaking"},
                 :c3 {:name "Reading"},
                 :c4 {:name "Writing"},
                 :c5 {:name "Vocabulary"},}]
      ^{:key k}
      [:div.row
       [:div.col-1>h7 (:name v)]
       [:div.col-11
        (doall 
         (for [[kk vv] {1 {:grade "A+"}
                        2 {:grade "A"}
                        3 {:grade "A-"}
                        4 {:grade "B+"}
                        5 {:grade "B"}
                        6 {:grade "B-"}
                        7 {:grade "C+"}
                        8 {:grade "C"}
                        9 {:grade "C-"}
                        10 {:grade "D+"}
                        11 {:grade "D"}
                        12 {:grade "D-"}
                        13 {:grade "F"}}]
           ^{:key [k kk]}
           [:label.custom-control.custom-checkbox.mb-2
            [:input.custom-control-input
             {:type "checkbox"
              :checked (if (= (s->g (get-in @edit-score [k]))
                              (:grade vv)) "checked" "")
              :onChange #(swap! edit-score assoc k (g->s (:grade vv)))
              }]
            [:span.custom-control-indicator ""]
            [:span.custom-control-description
             {:style {:background-color
                      (cond (some #(= % (:grade vv)) ["A"  ]) "green"
                            (some #(= % (:grade vv)) ["B"  ]) "lightgreen"
                            (some #(= % (:grade vv)) ["C"  ]) "yellow"
                            (some #(= % (:grade vv)) ["D"  ]) "pink"
                            (= (:grade vv) "F") "red")
                      :color (cond (some #(= % (:grade vv)) ["A"]) "white")}}
             (:grade vv)]]))]
       ]))])

(defn- score2 []
  [:div.row>div.col-12
   [:h6 "ability"]
   (doall 
    (for [[k v] {:a1 {:name "Independence"},
                 :a2 {:name "Logic     "},
                 :a3 {:name "Creativity"},
                 :a4 {:name "Team work "},
                 :a5 {:name "Confidence"},}]

      ^{:key k}
      [:div.row
       [:div.col-2>h7 (:name v)]
       [:div.col-10
        (doall 
         (for [[kk vv] {5 {:grade 5}
                        4 {:grade 4}
                        3 {:grade 3}
                        2 {:grade 2}
                        1 {:grade 1}}]
           ^{:key [k kk]}
           [:label.custom-control.custom-checkbox.mb-2
            [:input.custom-control-input
             {:type "checkbox"
              :checked (if (= (get-in @edit-score [k])
                              (:grade vv)) "checked" "")
              :onChange #(swap! edit-score assoc k (:grade vv))
              }]
            [:span.custom-control-indicator ""]
            [:span.custom-control-description
             {:style {:background-color
                      (cond (= (:grade vv) 5) "green"
                            (= (:grade vv) 4) "lightgreen"
                            (= (:grade vv) 3) "yellow"
                            (= (:grade vv) 2) "pink"
                            (= (:grade vv) 1) "red")
                      :color (cond (= (:grade vv) 5) "white")}}
             (:grade vv)]]))]
       ]))])


(defn- fedit-score [score]
  (if (= true (:show @edit-score))
    [:div.row.d-flex.justify-content-center>div.col-10.edit-score-panel
     [:div.edit-score-panel-title
      (if (= (:id @edit-score) 0)
        "NEW SCORE"
        "EDIT SCORE")]
     [:br]
     [cb-classes]
     [:br]
     [pickdatetime]
     [:br] [:br]
     [score1]
     [:br]
     [score2]
     [:br]
     [confirm-btn]]))

(defn edit []
  [:div.container
   [btns]
   [:br]
   ;; tooltip
   [score-boxes (:scores @student)]
   [fedit-score @edit-score]])

(defn- btns-advise []
  [:div.row>div.col-12
   [:a.btn.btn-info.m-2
    {:href "/teach/app/#/student"}
    "my students"]])

(defn- save-advise []
  (let [url (str "/teach/rest/student/" (:id @student) "/advise")
        p {:response-format :json
           :format :json
           :headers {:X-CSRF-TOKEN
                     (dommy/attr (sel1 "meta[name=\"csrf-token\"") "content")}
           :keywords? true
           :params {:advises @advises}
           :handler
           #(set! js/window.location.href "/teach/app/#/student" )}
        z (fn []
            (a/PUT url p))]
    (z)))

(defn edit-advise []
  [:div.container
   [btns-advise]
   [:div.row>div.col-12.advise
    [:br]
    [:div.row>div.col-10
     [:div.col-2 "advise 1"]
     [:div.col-10
      [:textarea
       {:on-change
        #(reset! advises
                 (assoc @advises :advise1 (.. % -target -value)))
        :value 
        (:advise1 @advises)}
       ]]]
    [:br]
    [:div.row>div.col-10
     [:div.col-2 "advise 2"]
     [:div.col-10 
      [:textarea
       {:on-change
        #(reset! advises
                 (assoc @advises :advise2 (.. % -target -value)))
        :value 
        (:advise2 @advises)}]]]
    [:div.row>div.col-12
     [:button.btn.btn-info.m-2
      {:on-click #'save-advise}
      "Confirm"]]]])


(defn- save-fgrade []
  (let [url (str "/teach/rest/student/" (:id @student) "/fgrade")
        p {:response-format :json
           :format :json
           :headers {:X-CSRF-TOKEN
                     (dommy/attr (sel1 "meta[name=\"csrf-token\"") "content")}
           :keywords? true
           :params {:fgrade @fgrade}
           :handler
           #(set! js/window.location.href "/teach/app/#/student" )}
        z (fn []
            (a/PUT url p))]
    (z)))

(defn- btns2 []
  [:div.row>div.col-12
   [:a.btn.btn-info.m-2
    {:href "/teach/app/#/student"}
    "my students"]])

(defn- edit-grades
  [item]
  [:div.col-12
   (doall 
    (for [[kk vv] {1 {:grade "A+"}
                   2 {:grade "A"}
                   3 {:grade "A-"}
                   4 {:grade "B+"}
                   5 {:grade "B"}
                   6 {:grade "B-"}
                   7 {:grade "C+"}
                   8 {:grade "C"}
                   9 {:grade "C-"}
                   10 {:grade "D+"}
                   11 {:grade "D"}
                   12 {:grade "D-"}
                   13 {:grade "F"}}]
      ^{:key [(:key item) kk]}
      [:label.custom-control.custom-checkbox.mb-2
       [:input.custom-control-input
        {:type "checkbox"
         :checked (if (= (s->g (get @fgrade (:key item)))
                         (:grade vv)) "checked" "")
         :onChange #(swap! fgrade assoc (:key item) (g->s (:grade vv)))
         }]
       [:span.custom-control-indicator ""]
       [:span.custom-control-description
        {:style {:background-color
                 (cond (some #(= % (:grade vv)) ["A"  ]) "green"
                       (some #(= % (:grade vv)) ["B"  ]) "lightgreen"
                       (some #(= % (:grade vv)) ["C"  ]) "yellow"
                       (some #(= % (:grade vv)) ["D"  ]) "pink"
                       (= (:grade vv) "F") "red")
                 :color (cond (some #(= % (:grade vv)) ["A"]) "white")}}
        (:grade vv)]]))] 
  )

(defn final-grade []
  [:div.container
   [btns2]
   [:div.row>div.col-12.advise
    (doall
     (for [item
           [{:key :final :title "verihuo Grade"}
            {:key :gpa_grade :title "GPA/Grade"}
            {:key :test_score :title "Test Scores"}
            {:key :course :title "Courses"}
            {:key :award :title "Honors & Awards"}
            {:key :activity :title "Extracurricular Activities"}
            {:key :work_exp :title "Work Experiences"}
            {:key :research_exp :title "Research Experiences"}]]
       ^{:key (:key item)}
       [:div.row
        [:div.col-12
         [:br]
         [:div.col-4 (:title item)]
         [:div.col-10 (edit-grades item)]]]))
    [:br]
    [:div.row>div.col-12
     [:div.col-4 "Liklihood of Acceptance"]
     [:div.col-12
      (doall
       (for [i [0 1 2 3 4 5]]
         (let [s (get-in @student [:fschools i])
               k (keyword (str "fschool" i "_percent"))]
           (cond (not (nil? s))
                 ^{:key i}
                 [:div.row
                  [:div.col-2 (:name s)]
                  [:div.col-8 [:input {
                                       :placeholder "pls input 0 to 100 point"
                                       :value (or (k @fgrade) "")
                                       :on-change #(->> % .-target .-value (swap! fgrade assoc k))}] "%"]]))))
      ]]
    [:br]
    [:br]
    [:div.row>div.col-12
     [:div.col-4 "Tips"]
     [:div.col-10
      (doall (for [item [{:key :tip1 :title "tips 1st"}
                         {:key :tip2 :title "tips 2nd"}
                         {:key :tip3 :title "tips 3rd"}]]
               ^{:key item}
               [:div.row
                [:div.col-2 (:title item)]
                [:div.col-10
                 [:input {:value (or ((:key item) @fgrade) "")
                          :onChange #(swap! fgrade assoc (:key item) 
                                            (-> % .-target .-value))}]]]))]]
    [:br]
    [:br]
    [:div.row>div.col-12
     [:div.col-4 "Recommanded School"]
     [:div.col-10
      (doall
       (for [item
             [{:key :recommend_school1 :title "recommanded 1st"}
              {:key :recommend_school2 :title "recommanded 2nd"}
              {:key :recommend_school3 :title "recommanded 3rd"}]]
         ^{:key (:key item)}
         [:div.row
          [:div.col-3 "recommanded 1st"]
          [:div.col-8 [:input
                       {:value (or (:recommend_school1 @fgrade) "")
                        :onChange #(swap! fgrade assoc (:key item)
                                          (-> % .-target .-value))}]]]))]]
    [:br]
    [:br]
    [:div.row>div.col-12
     [:div.col-4 "Action Plan"]
     [:div.col-10
      [:div.row
       [:textarea {:value (or (:action_plan @fgrade) "")
                   :onChange #(swap! fgrade assoc :action_plan
                                          (-> % .-target .-value))}]
       ]]]
    [:div.row>div.col-12
     [:button.btn.btn-info.m-2
      {:on-click #'save-fgrade}
      "Confirm"]]]])

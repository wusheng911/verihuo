(ns verihuo.teach.pages.dashboard
  (:require [reagent.core :as r]))

(defn main []
  [:div.container
   [:p "Hi, here is ur dashboard!"]])

;; (defn about-page []
;;   [:div.container
;;    [:div.row
;;     [:div.col-md-12
;;      [:img {:src (str js/context "/img/warning_clojure.png")}]]]
;;    ])

;; (defn home-page []
;;   [:div.container
;;    (when-let [docs (session/get :docs)]
;;      [:div.row>div.col-sm-12
;;       [:div {:dangerouslySetInnerHTML
;;              {:__html "hi"}}]])])

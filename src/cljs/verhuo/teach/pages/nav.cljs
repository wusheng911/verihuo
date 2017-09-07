(ns verihuo.teach.pages.nav
  (:require [reagent.core :as r]
            [reagent.session :as session]))

(defn nav-link [uri title page]
  [:li.nav-item
   {:class (when (= page (session/get :page)) "active")}
     [:a.nav-link
      {:href uri}
      title]])

(defn navbar []
  [:nav.navbar.navbar-dark.bg-default
   {:color "faded"}
   [:ul.nav
    [nav-link "/teach/app/#/" "My dashboard" ""]
    [nav-link "/teach/app/#/student" "My students" ""]]])


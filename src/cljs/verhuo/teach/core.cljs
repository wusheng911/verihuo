(ns verihuo.teach.core
  (:require [reagent.core :as r]
            [cljsjs.moment]
            [cljsjs.react-transition-group]
            [cljsjs.react-datetime]
            [verihuo.teach.route :as route]
            [verihuo.teach.pages.nav :as nav]
            [verihuo.teach.pages.dashboard :as dashboard]
            [verihuo.teach.pages.student :as student]
            [reagent.session :as session]
            [secretary.core :as secretary :include-macros true]
            [ajax.core :refer [GET POST]]))

(enable-console-print!)

(defn navbar []
  (let [collapsed? (r/atom true)]
    (fn []
      (nav/navbar))))

(def pages
  {:dashboard #'dashboard/main
   :student #'student/main
   :student-edit #'student/edit
   :edit-advise #'student/edit-advise
   :student-finalgrade #'student/final-grade})

(defn page []
  [(pages (session/get :page))])

(defn mount-components []
  (r/render [#'nav/navbar] (.getElementById js/document "navbar"))
  (r/render [#'page] (.getElementById js/document "app")))

(defn init! []
  (route/hook-browser-navigation!)
  (mount-components))

(init!)

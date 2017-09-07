(ns verihuo.teach.authorize
  (:require [reagent.session :as session]
            [ajax.core :as a]))
            


(defn login? []

  (let [teacher nil]
    (a/GET (str "/teach/rest/logininfo" )
           {:response-format :json
            :keywords? true
            :error-handler #(set! js/window.location.href "/teach/login")
            :handler
            #(session/put! :auth (:teacher %))})
    (session/get :auth)))


(defproject verihuo "0.1.0-SNAPSHOT"
  :description "FIXME: write this!"
  :url "http://example.com/FIXME"
  :license {:name "Eclipse Public License"
            :url "http://www.eclipse.org/legal/epl-v10.html"}

  :min-lein-version "2.7.1"

  :dependencies [[org.clojure/clojure "1.8.0"]
                 [org.clojure/clojurescript "1.9.562"]
                 [org.clojure/core.async  "0.3.442"
                  :exclusions [org.clojure/tools.reader]]
                 [mount "0.1.11"]
                 [clj-time "0.13.0"]
                 [cljsjs/moment "2.17.1-1"]
                 [cljs-ajax "0.6.0"]
                 [reagent "0.7.0"]
                 [cljsjs/react-transition-group "1.1.3-0"]
                 [cljsjs/react-datetime "2.8.10-0" :exclusions [cljsjs/react-dom]]
                 [prismatic/dommy "1.1.0"]
                 [secretary "1.2.3"]
                 [reagent-utils "0.2.1"]]

  :plugins [[lein-figwheel "0.5.10"]
            [lein-cljsbuild "1.1.5" :exclusions [[org.clojure/clojure]]]]

  :source-paths ["src"]

  :cljsbuild {:builds
              [{:id "dev"
                :source-paths ["src/cljs"]

                :figwheel {:autoload true}
                :compiler {:main "verihuo.teach.core"
                           :asset-path "/assets/js/teach/out"
                           :output-to "resources/compiled/assets/js/teach/app.js"
                           :output-dir "resources/compiled/assets/js/teach/out"
                           :optimizations :none
                           :pretty-print  true
                           :source-map-timestamp true
                           :preloads [devtools.preload]}}

               ;; This next build is an compressed minified build for
               ;; production. You can build this with:
               ;; lein cljsbuild once min
               {:id "prod"
                :source-paths ["src/cljs"]
                :compiler {;;:main "verihuo.teach.core"
                           :output-to "public/assets/js/teach/app.js"
                           :optimizations :advanced
                           :infer-externs true
                           :pretty-print true
                           :externs ["externs.js"]}}]}

  :figwheel {;; :http-server-root "public" ;; default and assumes "resources"
             ;; :server-port 3449 ;; default
             ;; :server-ip "127.0.0.1"

             :css-dirs ["public/assets/css/teach"] ;; watch and update CSS

             ;; Start an nREPL server into the running figwheel process
             ;; :nrepl-port 7888

             ;; Server Ring Handler (optional)
             ;; if you want to embed a ring handler into the figwheel http-kit
             ;; server, this is for simple ring servers, if this

             ;; doesn't work for you just run your own server :) (see lein-ring)

             ;; :ring-handler hello_world.server/handler

             ;; To be able to open files in your editor from the heads up display
             ;; you will need to put a script on your path.
             ;; that script will have to take a file path and a line number
             ;; ie. in  ~/bin/myfile-opener
             ;; #! /bin/sh
             ;; emacsclient -n +$2 $1
             ;;
             ;; :open-file-command "myfile-opener"

             ;; if you are using emacsclient you can just use
             ;; :open-file-command "emacsclient"

             ;; if you want to disable the REPL
             ;; :repl false

             ;; to configure a different figwheel logfile path
             ;; :server-logfile "tmp/logs/figwheel-logfile.log"

             ;; to pipe all the output to the repl
             ;; :server-logfile false
             }


  ;; setting up nREPL for Figwheel and ClojureScript dev
  ;; Please see:
  ;; https://github.com/bhauman/lein-figwheel/wiki/Using-the-Figwheel-REPL-within-NRepl
  :profiles {:dev {:dependencies [[binaryage/devtools "0.9.2"]
                                  [figwheel-sidecar "0.5.10"]
                                  [com.cemerick/piggieback "0.2.1"]]
                   ;; need to add dev source path here to get user.clj loaded
                   :source-paths ["src/cljs" "dev/cljs"]
                   ;; for CIDER
                   :plugins [[cider/cider-nrepl "0.12.0"]]
                   :repl-options {:nrepl-middleware [cemerick.piggieback/wrap-cljs-repl]}
                   ;; need to add the compliled assets to the :clean-targets
                   :clean-targets ^{:protect false} ["resources/compiled/assets/js/teach"
                                                     :target-path]}})

(ns verihuo.teach.score
  (:require [clojure.string :as str]))

(defn s->g
  [s]
  "score -> grade"
  (cond (>= s 97) "A+"
        (>= s 94) "A"
        (>= s 90) "A-"
        (>= s 87) "B+"
        (>= s 84) "B"
        (>= s 80) "B-"
        (>= s 77) "C+"
        (>= s 74) "C"
        (>= s 70) "C-"
        (>= s 67) "D+"
        (>= s 64) "D"
        (>= s 60) "D-"
        :else     "F"))

(defn g->s
  [grade]
  "g->s grade -> score"
  (let [g (str/upper-case grade)]
    (cond (= g "A+") 98
          (= g "A" ) 95
          (= g "A-") 92
          (= g "B+") 88
          (= g "B" ) 85
          (= g "B-") 82
          (= g "C+") 78
          (= g "C" ) 75
          (= g "C-") 72
          (= g "D+") 68
          (= g "D" ) 65
          (= g "D-") 62
          (= g "F" ) 0)))

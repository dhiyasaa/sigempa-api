import sys
import json
import joblib
import os
import numpy as np
import tensorflow as tf

from tensorflow.keras.models import load_model
from tensorflow.keras.layers import Layer
import tensorflow.keras.backend as K


# ==========================
# Clustering Layer DEC
# ==========================
class ClusteringLayer(Layer):

    def __init__(self, n_clusters, alpha=1.0, **kwargs):
        super().__init__(**kwargs)
        self.n_clusters = n_clusters
        self.alpha = alpha

    def build(self, input_shape):
        input_dim = input_shape[1]

        self.clusters = self.add_weight(
            shape=(self.n_clusters, input_dim),
            initializer="glorot_uniform",
            trainable=True,
            name="clusters"
        )

    def call(self, inputs):

        q = 1.0 / (
            1.0 +
            (
                K.sum(
                    K.square(
                        K.expand_dims(inputs, axis=1) - self.clusters
                    ),
                    axis=2
                ) / self.alpha
            )
        )

        q = q ** ((self.alpha + 1.0) / 2.0)

        q = K.transpose(
            K.transpose(q) / K.sum(q, axis=1)
        )

        return q


# ==========================
# Load Model
# ==========================

import os

BASE_DIR = os.path.dirname(os.path.abspath(__file__))

scaler = joblib.load(
    os.path.join(BASE_DIR, "scaler.pkl")
)

encoder = load_model(
    os.path.join(BASE_DIR, "encoder.keras"),
    compile=False
)

dec_model = load_model(
    os.path.join(BASE_DIR, "dec_model.keras"),
    custom_objects={
        "ClusteringLayer": ClusteringLayer
    },
    compile=False
)


# ==========================
# Input
# ==========================

magnitudo = float(sys.argv[1])
kedalaman = float(sys.argv[2])

X = np.array([[magnitudo, kedalaman]])

X_scaled = scaler.transform(X)


# ==========================
# Predict DEC
# ==========================

q = dec_model.predict(
    X_scaled,
    verbose=0
)

cluster = int(np.argmax(q))


mapping = {
    0: {
        "label": "Risiko Rendah",
        "status": "AMAN",
        "color": "#22C55E"
    },
    1: {
        "label": "Risiko Sedang",
        "status": "WASPADA",
        "color": "#FACC15"
    },
    2: {
        "label": "Risiko Tinggi",
        "status": "SIAGA",
        "color": "#EF4444"
    }
}


result = {
    "cluster": cluster,
    "label": mapping[cluster]["label"],
    "status": mapping[cluster]["status"],
    "color": mapping[cluster]["color"]
}


print(json.dumps(result))
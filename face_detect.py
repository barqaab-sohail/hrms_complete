# face_detect.py
import sys
import cv2
import numpy as np
import requests
import os

def detect_face(image_path):
    face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + "haarcascade_frontalface_default.xml")

    # Check if the input is a URL or local path
    if image_path.startswith("http://") or image_path.startswith("https://"):
        try:
            response = requests.get(image_path)
            image_array = np.asarray(bytearray(response.content), dtype=np.uint8)
            image = cv2.imdecode(image_array, cv2.IMREAD_COLOR)
        except:
            print("error")
            return
    else:
        if not os.path.exists(image_path):
            print("error")
            return
        image = cv2.imread(image_path)

    if image is None:
        print("error")
        return

    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    faces = face_cascade.detectMultiScale(gray, 1.3, 5)

    if len(faces) > 0:
        print("true")
    else:
        print("false")

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python face_detect.py <image_path>")
    else:
        detect_face(sys.argv[1])

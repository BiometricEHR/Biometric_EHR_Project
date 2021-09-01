import tempfile
from pyfingerprint.pyfingerprint import PyFingerprint
import mysql.connector
from utils import get_encoder
from utils import cosine_distance
import matplotlib.pyplot as plt
import cv2
import numpy as np

## Changes made for IDP2 ##
# line 23: change database
# line 77: update table details


##############################################################################
# Connect to database

# database part
mydb = mysql.connector.connect(
  host="192.168.1.115",
  user="jiachun",
  password="1234567890",
  # database="idp_final"
  database="idp2_trial1"
)

# get encoding stored in database
mycursor = mydb.cursor()

##############################################################################
# function to scan fingerprint

def scan_fingerprint():
  # Tries to initialize the sensor
  try:
      f = PyFingerprint('/dev/ttyUSB0', 57600, 0xFFFFFFFF, 0x00000000)
  
      if ( f.verifyPassword() == False ):
          raise ValueError('The given fingerprint sensor password is wrong!')
  
  except Exception as e:
      print('The fingerprint sensor could not be initialized!')
      print('Exception message: ' + str(e))
      exit(1)
  
  ## Gets some sensor information
  print('Currently used templates: ' + str(f.getTemplateCount()) +'/'+ str(f.getStorageCapacity()))
  
  ## Tries to read image and download it
  try:
      print('Waiting for finger...')
  
      ## Wait that finger is read
      while ( f.readImage() == False ):
          pass
  
      print('Downloading image (this take a while)...')
  
      imageDestination =  tempfile.gettempdir() + '/fingerprint.bmp'
      f.downloadImage(imageDestination)
  
      print('The image was saved to "' + imageDestination + '".')
  
  except Exception as e:
      print('Operation failed!')
      print('Exception message: ' + str(e))
      exit(1)

##############################################################################
# Get IC from database, and get encoding

# Declare hospital ID (hardcoded)
hospital_id = '0000001A'

# Get Patient IC
# mycursor.execute("SELECT * FROM hardware")
mycursor.execute("SELECT patient_ic FROM hardware WHERE rpi_id='rpi001'")
myresult = mycursor.fetchall() 
patient_ic = str(myresult[0][0]) 

# get encoder
weight_path = r'/home/pi/effnet_package_weight.h5' 
encoder = get_encoder(weight_path)

# get fingerprint
scan_fingerprint()

# read image
img_path = r'/tmp/fingerprint.bmp' 
img = plt.imread(img_path)
img = cv2.resize(img, (96, 96))
img = np.expand_dims(img, axis = [0, -1])

# get encoding
enc_anchor = encoder(img)
enc_anchor = np.squeeze(enc_anchor)
encodings = ''
for enc in enc_anchor:
  encodings += "{:.6f}".format(enc)
  encodings += ','
encodings = encodings[:-1]
##

##############################################################################
# Register patient to database (without check in)
sql = "UPDATE patient SET fingerprint_encoding = %s WHERE patient_ic = %s"
val = (encodings, patient_ic)
mycursor.execute(sql, val)
mydb.commit()

print(mycursor.rowcount, "record inserted.")
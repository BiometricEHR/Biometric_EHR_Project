from tensorflow.keras.models import Model
from tensorflow.keras.layers import Input, Dense, Flatten, Conv2D, MaxPooling2D, Concatenate
from tensorflow.keras.layers import Dropout, BatchNormalization
from tensorflow.keras import regularizers
from efficientnet.tfkeras import EfficientNetB0

def get_encoder(path):
  
  def left_model(input):
    # EfficientNetB0
    effnetb0 = EfficientNetB0(
        input_tensor=input,
        include_top=False,
        weights='imagenet', 
        pooling = 'avg',
    )

    left = Dense(500, activation= 'relu')(effnetb0.output)
    left = Dropout(0.2)(left) 

    # Use the EfficientNetB0 from the last 30 layers to train with
    effnetb0.trainable = False
    for layer in effnetb0.layers[-30:]:
        layer.trainable = True

    return left

  def right_model(input):
    right = Conv2D(32, (5, 5), activation='relu', kernel_regularizer=regularizers.l2(0.001))(input)
    right = BatchNormalization()(right)
    right = MaxPooling2D((2, 2))(right)
    right = Conv2D(64,(5, 5), activation='relu', kernel_regularizer=regularizers.l2(0.001))(right)
    right = BatchNormalization()(right)
    right = MaxPooling2D((2, 2))(right)
    right = Conv2D(128,(3, 3), activation='relu', kernel_regularizer=regularizers.l2(0.001))(right)
    right = BatchNormalization()(right)
    right = MaxPooling2D((2, 2))(right)
    right = Dropout(0.3)(right)
    right = Flatten()(right)
    right = Dense(200, activation='relu')(right) # default is 256
    right = Dropout(0.4)(right)
    return right
  
  img_shape = (96, 96, 1)
  encoder_input = Input(shape=img_shape)
  conc_input = Concatenate()([encoder_input, encoder_input, encoder_input])

  left = left_model(conc_input)
  right = right_model(conc_input)
  encoder_output = Concatenate()([left,right])

  encoder = Model(encoder_input, encoder_output)
  
  encoder.load_weights(path)

  return encoder

import tensorflow.keras.backend as K
def cosine_distance(vects):
  '''
  PURPOSE:
  to create a layer to measure the cosine distance
  
  args:
  1) vects
  - contains two tensors
  - the tensors are the encoding 1 and 2 respectively
  '''
  x, y = vects

  xy_dot = K.sum(x * y, axis = 1, keepdims = True)
  x_mag = K.sqrt(K.sum(K.square(x), axis = 1, keepdims = True))
  y_mag = K.sqrt(K.sum(K.square(y), axis = 1, keepdims = True))

  cos_sim = xy_dot / K.maximum((x_mag * y_mag), K.epsilon())
  cos_dist = 1 - cos_sim

  return cos_dist
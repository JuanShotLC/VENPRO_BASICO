import { Component } from '@angular/core';
import {IonicPage, ModalController, NavController, NavParams, ToastController} from 'ionic-angular';
import {LoginPage} from "../login/login";
import {SliderPage} from "../slider/slider";
import {ApiProvider} from "../../providers/api/api";
import {AlertPage} from "../alert/alert";
import { Geolocation } from '@ionic-native/geolocation';



/**
 * Generated class for the RegisteruserPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-registeruser',
  templateUrl: 'registeruser.html',
})
export class RegisteruserPage {

  registerdato = {email: null, password: null, telefono: '34 ', nombre: null,  apellido: null,direccion: null, latitude:null,longitude:null,password2:null, direccion2: null};
  myParam = {valor:null,messaje:null,fontipe:null,volver:null};

  constructor( private geolocation2: Geolocation ,public navCtrl: NavController, public navParams: NavParams,public api: ApiProvider,public modalCtrl: ModalController, public toastCtrl: ToastController) {

  
    this.iniciar();
    
  }
  iniciar(){
    this.geolocation2.getCurrentPosition().then((resp) => {
      this.registerdato.latitude=resp.coords.latitude;
      this.registerdato.longitude= resp.coords.longitude;
      console.log(resp);
    }).catch((error) => {
      console.log('Error getting location', error);
    });

  }


  atras() {
    this.navCtrl.setRoot(LoginPage);
  }

  register(){
    console.log(this.registerdato);
    if(this.registerdato.password2 == this.registerdato.password){
      this.api.postinfo('api/datosuser',this.registerdato,true).then((res: any) => {
        console.log(res);
        if (res.status == 'success') {
          this.registerdato = {email: null, password: null, telefono: null, nombre: null,  apellido: null,direccion: null, latitude:null,longitude:null,password2:null,direccion2: null};
          this.myParam.valor = status;
          // this.myParam.volver = 'eventos';
          this.myParam.messaje = 'BIEN, YA PUEDES INGRESAR A APATXEE';
          this.myParam.fontipe = 3;
          let myModal = this.modalCtrl.create(AlertPage, {'myParam': this.myParam});
          myModal.present();
        }else{
          this.presentToast(res.msj);
        }
      });
    }else{
      this.presentToast("Las contraseñas no son iguales");
    }



  }


  presentToast(msg) {
    let toast = this.toastCtrl.create({
      message: msg,
      duration: 3000,
      position: 'bottom'
    });

    toast.onDidDismiss(() => {
      console.log('Dismissed toast');
    });

    toast.present();
  }
}

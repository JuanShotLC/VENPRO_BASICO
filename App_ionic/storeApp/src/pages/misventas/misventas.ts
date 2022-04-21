import { Component } from '@angular/core';
import {AlertController, IonicPage, ModalController, NavController, NavParams} from 'ionic-angular';
import {NativePageTransitions} from "@ionic-native/native-page-transitions";
import {ApiProvider} from "../../providers/api/api";
import {CookieService} from "angular2-cookie/core";
import {EventosPage} from "../eventos/eventos";

/**
 * Generated class for the MisventasPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-misventas',
  templateUrl: 'misventas.html',
})
export class MisventasPage {
  url:any;
  contador:any;
  eventoslist:any;
  session_data:any;
  constructor(public navCtrl: NavController, public navParams: NavParams, public modalCtrl: ModalController,
                public _cookieService: CookieService, public api: ApiProvider,
                private nativePageTransitions: NativePageTransitions,private alertCtrl: AlertController) {
    this.url= this.api.api_url;
    this.session_data=JSON.parse(this._cookieService.get('session'));
    if(this.session_data){
      this.loadData(this.session_data.id);
    }
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad MisventasPage');
  }
  loadData(id) {
    this.api.getinfo('/api/getdataventas/'+id,{id:id}).then((res: any) => {
      this.eventoslist = res;
      this.contador =  this.eventoslist.length;
    });
  }
  atras2() {
    this.navCtrl.setRoot(EventosPage);
  }
  presentConfirm(id, telefono, address, latitude,longitude,name, email) {
    console.log(id);
    console.log(telefono);
    console.log(address);
    console.log(latitude);
    console.log(longitude);
    console.log(name);
    console.log(email);
    let alert = this.alertCtrl.create({
      title: 'Datos del cliente',
      message: '<div>Nombre: ' + name +'</div><div>Telefono: ' + telefono +'</div><div>Email: ' + email +'</div><div>Direccion: ' + address +'</div>',
      buttons: [
        {
          text: 'Cancel',
          role: 'cancel',
          handler: () => {
            console.log('Cancel clicked');
          }
        },
        {
          text: 'Ver Mapa',
          handler: () => {
            window.open('https://maps.google.com/?q='+latitude+','+longitude+'', '_blank');
            console.log('Buy clicked');
          }
        }
      ]
    });
    alert.present();
  }

}

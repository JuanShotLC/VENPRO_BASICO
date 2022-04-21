import { Component } from '@angular/core';
import {AlertController, IonicPage, ModalController, NavController, NavParams} from 'ionic-angular';
import {CookieService} from "angular2-cookie/core";
import {ApiProvider} from "../../providers/api/api";

/**
 * Generated class for the ProductoPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-producto',
  templateUrl: 'producto.html',
})
export class ProductoPage {
  hola: any;
  info:any;
  postres:any;
  myParam = {valor:null,messaje:null,fontipe:null,volver:null};
  session_data:any;
  iduser:any;
  idreser:any;
  id:any;
  url:any;
  constructor( public navCtrl: NavController,
              public navParams: NavParams,public _cookieService:CookieService,
              public api:ApiProvider) {

    this.url = this.api.api_url;
    this.id = this.navParams.get('id');
    this.session_data = JSON.parse(this._cookieService.get('session'));
    this.iduser = this.session_data.id;
    console.log(this.iduser);
    if(this.id){
      this.api.getinfo('api/publicaciones/' + this.id,null).then((res: any) => {
        this.info=res;
        this.info.image=this.url+this.info.publicacion_imagen[0].ruta;
        console.log(this.info.image);
      });
    }


  }

  ionViewDidLoad() {
   this.hola="popo";
    console.log('ionViewDidLoad ProductoPage');
  }

}

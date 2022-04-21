import { Component } from '@angular/core';
import {IonicPage, ModalController, NavController, NavParams} from 'ionic-angular';
import {CanjePage} from "../canje/canje";
import {AlertPage} from "../alert/alert";
import {ApiProvider} from "../../providers/api/api";
import {CookieService} from "angular2-cookie/core";

/**
 * Generated class for the ConfircanjePage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-confircanje',
  templateUrl: 'confircanje.html',
})
export class ConfircanjePage {
  myParam = {valor:null,messaje:null,fontipe:null,volver:null};
  codes:any;
  total:any;
  session_data:any;
  iduser:any;
  constructor(public navCtrl: NavController, public navParams: NavParams,public modalCtrl: ModalController,public api:ApiProvider,public _cookieService:CookieService) {

    this.session_data=JSON.parse(this._cookieService.get('session'));
    this.iduser=this.session_data.ID;

    this.codes=this.navParams.get('codes');
    this.codes={codes:this.codes}
    this.total=this.navParams.get('total');
  }
  openModalWithParams(valor) {

    this.api.postinfo('/store/cart/?token='+this.session_data.token,this.codes).then((res: any) => {
      console.log(res);
      if (res.status_code == 200) {
        this.myParam.valor = status;
        this.myParam.volver = 'eventos';
        this.myParam.messaje = 'GRACIAS, VUELVE PRONTO.';
        this.myParam.fontipe = 2;
        let myModal = this.modalCtrl.create(AlertPage, {'myParam': this.myParam});
        myModal.present();
      }

    });
  }

  atras() {
    this.navCtrl.setRoot(CanjePage);
  }
}

import { Component } from '@angular/core';
import {IonicPage, NavController, NavParams, ViewController} from 'ionic-angular';
import {PerfilPage} from "../perfil/perfil";
import {EventosPage} from "../eventos/eventos";
import {MyservicePage} from "../myservice/myservice";

/**
 * Generated class for the AlertPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-alert',
  templateUrl: 'alert.html',
})
export class AlertPage {
  myParam: any;
  messaje: string;
  status: any;
  fontipe: any;
  volver:any;
  constructor(public navCtrl: NavController, params: NavParams,public viewCtrl: ViewController) {
    this.myParam = params.get('myParam');

    this.volver=this.myParam.volver;
    this.messaje=this.myParam.messaje;
    this.status=this.myParam.valor;
    this.fontipe=this.myParam.fontipe;

    console.log(this.myParam);
  }

  dismiss(volver) {
    console.log(volver);
    this.viewCtrl.dismiss();
    if(volver==true){
      this.navCtrl.setRoot(PerfilPage);
    }
    if(volver=='eventos'){
      this.navCtrl.setRoot(EventosPage);
    }
    if(volver=='service'){
      this.navCtrl.setRoot(MyservicePage);
    }


  }


}

import { Component } from '@angular/core';
import {IonicPage, NavController, NavParams } from 'ionic-angular';
import {EventosPage} from "../eventos/eventos";
import {ReservaservicePage} from "../reservaservice/reservaservice";
import {OperationsPage} from "../operations/operations";
import {ApiProvider} from "../../providers/api/api";
import {CookieService} from "angular2-cookie/core";

/**
 * Generated class for the MyservicePage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-myservice',
  templateUrl: 'myservice.html',
})
export class MyservicePage {

  servicelist ={};
  session_data:any;
  token:any;
  constructor(public navCtrl: NavController, public navParams: NavParams,public _cookieService:CookieService,public api:ApiProvider) {

    this.session_data=JSON.parse(this._cookieService.get('session'));
    this.token=this.session_data.token;

    this.api.getinfo('/service/' ,{token:this.token}).then((res: any) => {
      this.servicelist = res;
    });

  }

  goresevaservice(idmio){
    this.navCtrl.setRoot(ReservaservicePage,{id:idmio});
  }

  atras_() {
    this.navCtrl.setRoot(EventosPage);
  }
  gooperantion(){
    this.navCtrl.setRoot(OperationsPage);
  }

}

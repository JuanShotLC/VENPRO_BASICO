import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import {CanjePage} from "../canje/canje";

/**
 * Generated class for the QrPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-qr',
  templateUrl: 'qr.html',
})
export class QrPage {

  constructor(public navCtrl: NavController, public navParams: NavParams) {



  }



  atras() {
    this.navCtrl.setRoot(CanjePage);
  }

}

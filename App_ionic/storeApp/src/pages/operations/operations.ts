import { Component } from '@angular/core';
import {IonicPage, ModalController, NavController, NavParams} from 'ionic-angular';
import {EventosPage} from "../eventos/eventos";
import {AlertPage} from "../alert/alert";

/**
 * Generated class for the OperationsPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-operations',
  templateUrl: 'operations.html',
})
export class OperationsPage {
  myParam = {valor:null,messaje:null,fontipe:null};
  constructor(public navCtrl: NavController, public navParams: NavParams,public modalCtrl: ModalController) {
  }

  openModalWithParams(valor) {
    let status=valor;
    this.myParam.valor=status;
    this.myParam.messaje='EL VALOR TOTAL HA SIDO DEBITADO DE TU CUENTA ASOCIADA';
    this.myParam.fontipe=2;
    let myModal = this.modalCtrl.create(AlertPage, { 'myParam': this.myParam });
    myModal.present();
  }

  atras() {
    this.navCtrl.setRoot(EventosPage);
  }

}

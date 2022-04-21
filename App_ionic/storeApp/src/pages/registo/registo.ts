import { Component } from '@angular/core';
import {IonicPage, ModalController, NavController, NavParams} from 'ionic-angular';
import {EventosPage} from "../eventos/eventos";
import {PerfilPage} from "../perfil/perfil";
import {AlertPage} from "../alert/alert";

/**
 * Generated class for the RegistoPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-registo',
  templateUrl: 'registo.html',
})
export class RegistoPage {
  myParam = {valor:null,messaje:null,fontipe:null};
  constructor(public navCtrl: NavController, public navParams: NavParams,public modalCtrl: ModalController) {
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad RegistoPage');
  }
  openModalWithParams(valor) {
    let status=valor;
    this.myParam.valor=status;
    this.myParam.messaje='TU MÉTODO DE PAGO HA SIDO INGRESADO CORRECTAMENTE';
    this.myParam.fontipe=3;
    let myModal = this.modalCtrl.create(AlertPage, { 'myParam': this.myParam });
    myModal.present();
  }

  atras() {
    this.navCtrl.setRoot(PerfilPage);
  }
}

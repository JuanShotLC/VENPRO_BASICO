import {Component} from '@angular/core';
import {IonicPage, ModalController, NavController, NavParams, ToastController} from 'ionic-angular';
import {EventosPage} from "../eventos/eventos";
import {PerfilPage} from "../perfil/perfil";
import {AlertPage} from "../alert/alert";
import {ApiProvider} from "../../providers/api/api";
import {Storage} from "@ionic/storage";

/**
 * Generated class for the MessajePage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
    selector: 'page-messaje',
    templateUrl: 'messaje.html',
})
export class MessajePage {
    session_data: any;
    myParam = {valor: null, messaje: null, fontipe: null};
    data_msg = {subject:null,message:null}

    constructor(public navCtrl: NavController,public toastCtrl: ToastController, public navParams: NavParams, private storage: Storage, public modalCtrl: ModalController, public api: ApiProvider) {
        this.storage.get('session').then(session => {
            this.session_data = JSON.parse(session);
        });
    }

    openModalWithParams(valor) {
        let status = valor;
        this.myParam.valor = status;
        this.myParam.messaje = 'HUY PRONTO NOS PONDREMOS EN CONTACTO.';
        this.myParam.fontipe = 2;
        let myModal = this.modalCtrl.create(AlertPage, {'myParam': this.myParam});
        myModal.present();
    }

    ionViewDidLoad() {
        console.log('ionViewDidLoad MessajePage');
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

    atras() {
        this.navCtrl.setRoot(PerfilPage);
    }

    sendMsg() {
        let body = {
            "subject": this.data_msg.subject,
            "message": this.data_msg.message
        }

        this.api.postinfo('/message/?token=' + this.session_data.token, body).then((res: any) => {
            if (res.status_code == 200) {
                this.navCtrl.setRoot(PerfilPage);
                this.openModalWithParams(null);
            } else {
                this.presentToast("Ups! algo ocurrio");
            }
        });
    }

}

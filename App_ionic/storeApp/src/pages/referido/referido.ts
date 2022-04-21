import {Component} from '@angular/core';
import {IonicPage, NavController, NavParams, ToastController} from 'ionic-angular';
import {PerfilPage} from "../perfil/perfil";
import {Storage} from "@ionic/storage";
import {ApiProvider} from "../../providers/api/api";

/**
 * Generated class for the ReferidoPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
    selector: 'page-referido',
    templateUrl: 'referido.html',
})
export class ReferidoPage {
    referdata = {first_name: null, email: null};
    session_data: any;

    constructor(public navCtrl: NavController,public toastCtrl: ToastController, public navParams: NavParams,private storage: Storage,public api:ApiProvider) {
        this.storage.get('session').then(session => {
            this.session_data = JSON.parse(session);
            });
    }

    close() {
        this.navCtrl.setRoot(PerfilPage);
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

    sendRefer() {
        let body = {
            "first_name": this.referdata.first_name,
            "email": this.referdata.email,
            "referred": true
        }
        this.api.postinfo('/user/?token=' + this.session_data.token, body).then((res: any) => {
            if (res.status_code == 200) {
                this.navCtrl.setRoot(PerfilPage);
                this.presentToast("Se envio el referido");
            }else{
                this.presentToast("Ups! algo ocurrio");
            }
        });
    }
}

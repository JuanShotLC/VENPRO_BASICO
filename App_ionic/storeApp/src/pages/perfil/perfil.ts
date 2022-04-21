import {Component} from '@angular/core';
import {IonicPage, ModalController, NavController, NavParams, Platform, ToastController} from 'ionic-angular';
import {EventosPage} from "../eventos/eventos";
import {AlertdeletePage} from "../alertdelete/alertdelete";
import {ReferidoPage} from "../referido/referido";
import {MessajePage} from "../messaje/messaje";
import {CalendarPage} from "../calendar/calendar";
import {PerfilformPage} from "../perfilform/perfilform";
import {MyshoppingPage} from "../myshopping/myshopping";
import {RegistoPage} from "../registo/registo";
import {MisserviciosPage} from "../misservicios/misservicios";
import {MyreservasPage} from "../myreservas/myreservas";
import {Storage} from "@ionic/storage";
import { Camera, Crop } from 'ionic-native';
import {ApiProvider} from "../../providers/api/api";
import { LoadingController} from 'ionic-angular';
import { FileTransfer, FileUploadOptions, FileTransferObject } from '@ionic-native/file-transfer';
import { File } from '@ionic-native/file';
/**
 * Generated class for the PerfilPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
    selector: 'page-perfil',
    templateUrl: 'perfil.html',
})
export class PerfilPage {
    myParam: any;
    session_data: any;
    imgP:any;
    datosuser = {name: null, phone: null, email: null, token: null, ID: null, password: null};


    public options: any = {
        allowEdit: true,
        sourceType: Camera.PictureSourceType.CAMERA,
        mediaType: Camera.MediaType.ALLMEDIA,
        destinationType: Camera.DestinationType.FILE_URI
    }

    constructor(public navCtrl: NavController,private transfer: FileTransfer,public toastCtrl: ToastController,public platform: Platform,public loadingCtrl: LoadingController, private storage: Storage, public navParams: NavParams, public modalCtrl: ModalController,public api:ApiProvider) {

        this.storage.get('session').then(session => {
            this.session_data = JSON.parse(session);
            console.log(this.session_data)
          if(!this.session_data.avatar){
            this.imgP = 'assets/icon/perfil.jpg';
          }else{
            this.imgP = this.session_data.settings.avatar;
          }
            this.datosuser.name = this.session_data.name;
            this.datosuser.email = this.session_data.email;
        });
    }

    atras2() {
        console.log('toko nuevamnete')
        this.navCtrl.setRoot(EventosPage);
    }

    deletecount() {
        this.navCtrl.setRoot(AlertdeletePage);
    }

    refer() {
        this.navCtrl.setRoot(ReferidoPage);
    }

    gomessaje() {
        this.navCtrl.setRoot(MessajePage);
    }

    gocalendar() {
        let myModal = this.modalCtrl.create(CalendarPage, {'myParam': this.myParam});
        myModal.present();
    }

    goformperfil() {
        this.navCtrl.setRoot(PerfilformPage);
    }

    gomishopping() {

        this.navCtrl.setRoot(MyshoppingPage, {id_vendedor:null ,tipo:'misproductos'});
    }

    gomireserva() {
        this.navCtrl.setRoot(MyreservasPage);
    }


    goregistro() {
        this.navCtrl.setRoot(RegistoPage);
    }

    gomisreservas() {
        this.navCtrl.setRoot(MisserviciosPage);
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
    //Imagen de Perfil

    // Return a promise to catch errors while loading image



}

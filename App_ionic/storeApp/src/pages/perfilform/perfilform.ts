import { Component } from '@angular/core';
import {IonicPage, LoadingController, NavController, NavParams, Platform, ToastController} from 'ionic-angular';
import {EventosPage} from "../eventos/eventos";
import {PerfilPage} from "../perfil/perfil";
import {Http} from "@angular/http";
import {ApiProvider} from "../../providers/api/api";
import {CookieService} from "angular2-cookie/core";
import {FileTransfer, FileTransferObject, FileUploadOptions} from "@ionic-native/file-transfer";
import { Camera, Crop } from 'ionic-native';
import {Storage} from "@ionic/storage";
import { Geolocation } from '@ionic-native/geolocation';

/**
 * Generated class for the PerfilformPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-perfilform',
  templateUrl: 'perfilform.html',
})
export class PerfilformPage {

    datosuser={id:null,nombre:null,apellido:null,latitude:null ,longitude:null, direccion:null,telefono:null,email:null,token:null,password:null,avatar:null,direccion2:null};
    r:any;
    session_data:any;
    token:any;
    imgP:any;
    public options: any = {
        allowEdit: true,
        sourceType: Camera.PictureSourceType.CAMERA,
        mediaType: Camera.MediaType.ALLMEDIA,
        destinationType: Camera.DestinationType.FILE_URI
    }

  constructor(private geolocation: Geolocation, public http: Http,public navCtrl: NavController,private storage: Storage,private transfer: FileTransfer, public navParams: NavParams,public platform: Platform,public loadingCtrl: LoadingController,public api:ApiProvider, public toastCtrl: ToastController,public _cookieService:CookieService) {





    this.storage.get('session').then(session => {
      this.session_data = JSON.parse(session);
      console.log(this.session_data);
      this.datosuser.nombre = this.session_data.name;
      this.datosuser.id = this.session_data.id;
      this.datosuser.apellido = this.session_data.apellido;

      if(!this.session_data.avatar || this.session_data.avatar==0 ){
        this.imgP = 'assets/icon/perfil.jpg';
      }else{
        this.imgP = this.session_data.avatar;
      }
      this.datosuser.direccion=this.session_data.direccion;
      this.datosuser.email=this.session_data.email;
      this.datosuser.latitude=this.session_data.latitude;
      this.datosuser.longitude=this.session_data.longitude;
      this.datosuser.telefono=this.session_data.telefono;
      this.datosuser.direccion2=this.session_data.direccion2;
    });

    /*  this.storage.get('session').then(session => {
          this.session_data = JSON.parse(session);
          console.log(this.session_data);
          this.datosuser.first_name = this.session_data.first_name;
          this.imgP = this.session_data.settings.avatar;
          this.datosuser.first_name=this.session_data.first_name;
          this.datosuser.email=this.session_data.email;
          this.token=this.session_data.token;
          this.datosuser.token=this.session_data.token;
          this.datosuser.ID=this.session_data.ID;
      });
*/
  }

  actualizarcoor(){
    this.geolocation.getCurrentPosition().then((resp) => {
      this.datosuser.latitude=resp.coords.latitude;
      this.datosuser.longitude=resp.coords.longitude;
      console.log(resp);
    }).catch((error) => {
      console.log('Error getting location', error);
    });
  }

  perfil() {

    let body = this.datosuser;
    console.log(body);
    this.api.putinfo('api/datosuser', body).then((res: any) => {
      this.r = res;
      console.log(this.r);
      if (this.r.status == 'success') {
        this.session_data =this._cookieService.put('session', JSON.stringify(this.datosuser));
        this._cookieService.put('session', JSON.stringify(res.data));
        this.storage.set('session', JSON.stringify(res.data));
        this.presentToast("Se ha editado correctamente");
      } else {
        this.presentToast("Ocurrio un error");
      }
    });
  }

    getMedia(): Promise<any> {
        console.log('Camera')
        // Get Image from ionic-native's built in camera plugin
        return Camera.getPicture(this.options)
            .then((fileUri) => {
                // Crop Image, on android this returns something like, '/storage/emulated/0/Android/...'
                // Only giving an android example as ionic-native camera has built in cropping ability
                if (this.platform.is('ios')) {
                    return fileUri
                } else if (this.platform.is('android')) {
                    console.log(fileUri)
                    console.log('AQUI ENTRE ANDROID')
                    // Modify fileUri format, may not always be necessary
                    /* Using cordova-plugin-crop starts here */
                    return fileUri
                }
            })
            .then((path) => {
                // path looks like 'file:///storage/emulated/0/Android/data/com.foo.bar/cache/1477008080626-cropped.jpg?1477008106566'
                console.log('Cropped Image Path!: ' + path);
                this.imgP = path


                const fileTransfer: FileTransferObject = this.transfer.create();

                let options: FileUploadOptions = {
                    fileKey: 'image',
                    fileName: 'name.jpg',
                    headers: {},
                    params:{id:this.datosuser.id}
                }

                fileTransfer.upload(path, this.api.api_url+'/api/uploadp', options)
                    .then((data:any) => {
                        console.log(data);
                      if(data.status=='success'){
                        this.session_data.avatar = data.id;
                        this.storage.set('session', JSON.stringify(this.session_data));
                        //this.imgP = data.data.url;

                        this.session_data.avatar= data.id;
                        this.datosuser.avatar= data.id
                        this.session_data =this._cookieService.put('session', JSON.stringify(this.datosuser));
                        this.storage.set('session', JSON.stringify(data.user));
                        this.presentToast("Se ha editado correctamente");
                      }
                       /* this.api.putinfo('/user/'+this.session_data.ID+'/?token='+this.token, body).then((res: any) => {
                            this.r = res;
                            console.log(this.r);
                            if (this.r.status_code == 200) {
                                this.session_data =this._cookieService.put('session', JSON.stringify(this.datosuser));
                                this.presentToast("Se ha editado correctamente");
                            } else {
                                this.presentToast("Ocurrio un error");
                            }
                            loader.dismiss();
                        });*/

                    }, (err) => {
                        console.log(err);
                    });

                return path;

            })
    }

  atras() {
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

  }

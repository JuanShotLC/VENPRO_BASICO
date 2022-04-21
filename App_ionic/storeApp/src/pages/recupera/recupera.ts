import { Component } from '@angular/core';
import {IonicPage, NavController, NavParams, ToastController} from 'ionic-angular';
import {LoginPage} from "../login/login";
import {CookieService} from "angular2-cookie/core";
import {ApiProvider} from "../../providers/api/api";
import { RequestOptions, Http,Headers } from '@angular/http';
import 'rxjs/add/operator/map';
/**
 * Generated class for the RecuperaPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-recupera',
  templateUrl: 'recupera.html',
})
export class RecuperaPage {

  email:any=null;
  recuperadatos = { email: null};
  r:any;
  constructor(public http: Http,public navCtrl: NavController, public navParams: NavParams, public toastCtrl: ToastController,public _cookieService:CookieService,public api:ApiProvider) {

  }


  recupera() {

    this.email=this.recuperadatos.email;

    let body = {
      'email':this.email,
      }

      let params = body;

    console.log(params);

    this.api.postinfo('/api/recupera', body).then((res: any) => {

      this.r=res;
      console.log(this.r);
      if (this.r.status == 'success') {
        this.presentToast("Te enviamos un correo, puede tardar unos minutos");
      }else{
        this.presentToast("Ocurrio un error");
      }
    })

    /*--------*/
    /*let headers = new Headers();

    headers.append('Content-Type', 'application/json');

    let data=JSON.stringify({email:"jeanzum@gmail.com"});

    this.http.post('http://api.indexdigital.co/v1/auth/recover',data,headers)
      .map(res => res.json())
      .subscribe(res => {
        alert("success "+res);
      }, (err) => {
        alert("failed");
      });*/
    /*--------*/
  }

  atras(){
    this.navCtrl.setRoot(LoginPage);
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

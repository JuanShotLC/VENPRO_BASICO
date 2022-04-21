import { Component } from '@angular/core';
import {AlertController, IonicPage, NavController, NavParams, ToastController} from 'ionic-angular';
import {EventosPage} from "../eventos/eventos";
import {ConfircanjePage} from "../confircanje/confircanje";
import {QrPage} from "../qr/qr";
import { BarcodeScanner, BarcodeScannerOptions } from '@ionic-native/barcode-scanner';
import {CookieService} from "angular2-cookie/core";
import {ApiProvider} from "../../providers/api/api";

/**
 * Generated class for the CanjePage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-canje',
  templateUrl: 'canje.html',
})
export class CanjePage {

  options:BarcodeScannerOptions;
  results:any;
  session_data:any;
  token:any;
  products=[];
  total:number=0;
  total2:number;
  stock:any;
  stock2:any;
  codes=[];
  productos:any;

  constructor(public alertCtrl: AlertController,private  barcode:BarcodeScanner,public navCtrl: NavController, public navParams: NavParams,public _cookieService:CookieService,public api:ApiProvider, public toastCtrl: ToastController) {





    this.session_data=JSON.parse(this._cookieService.get('session'));
    this.token=this.session_data.token;
    if(this._cookieService.get('productos')!= null){
      this.productos=JSON.parse(this._cookieService.get('productos'));
      this.total=JSON.parse(this._cookieService.get('total'));
      console.log(this.total);
    }


    this.api.getinfo('/store/',{token:this.token} ).then((res: any) => {
      console.log(res);
    });

    if(this.productos!=null){
      this.products=this.productos;
    }

  }

  //codigo de barras

  async  scanBarcode(){
    this.results= await this.barcode.scan();

    if(!this.results.cancelled){
      this.api.getinfo('/store/'+this.results.text ,{token:this.token}).then((res: any) => {

        if(res.status==404){
          this.presentToast("Este Producto no esta registrado");
        }else {
          this.stock = parseFloat(res.stock);

          if (this._cookieService.get('stock2') != null) {
            this.stock2 = parseFloat(this._cookieService.get('stock2'));
          }
          console.log(this.stock2);

          if (this.stock > 0 && this.stock2 == null) {
            this.stock = this.stock - 1;
            this.stock2 = this._cookieService.put('stock2', this.stock);
            this.products.push(res);
            this.codes.push(res.ref);
            this.total2 = parseFloat(res.value);
            this.total = (this.total + this.total2);
          } else if (this.stock2 && this.stock2 > 0) {
            this.stock = this.stock2 - 1;
            this.stock2 = this._cookieService.put('stock2', this.stock);
            this.products.push(res);
            this.codes.push(res.ref);
            this.total2 = parseFloat(res.value);
            this.total = (this.total + this.total2);

          } else if(this.stock2==0 ||  this.stock==0){
            this.presentToast("Este Producto ha llegado a su limite");
          }
        }
      });
    }

  }

  atras() {

    let alert = this.alertCtrl.create({
      title: '¿Estas seguro de abandonar tu compra?',
      message: 'Si cancelas tu compra no podrás volver a esta lista de productos.',

      buttons: [
        {
          text: 'Cancelar',
          handler: (data: any) => {
            console.log('Cancel clicked');
          }
        },
        {
          text: 'Salir',
          handler: (data: any) => {
            this.navCtrl.setRoot(EventosPage);
          }
        }
      ]
    });
    alert.present();
  }


  confir() {
    this._cookieService.put('productos', JSON.stringify(this.products));
    this._cookieService.put('total', JSON.stringify(this.total));
    this.navCtrl.setRoot(ConfircanjePage,{codes:this.codes,total:this.total});
  }

  qr() {
    this.navCtrl.setRoot(QrPage);
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

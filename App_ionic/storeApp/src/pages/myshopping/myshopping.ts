import { Component } from '@angular/core';
import {AlertController, IonicPage, NavController, NavParams} from 'ionic-angular';
import {EventosPage} from "../eventos/eventos";
import {PerfilPage} from "../perfil/perfil";
import {CookieService} from "angular2-cookie/core";
import {ApiProvider} from "../../providers/api/api";
import {DetailreservaPage} from "../detailreserva/detailreserva";

/**
 * Generated class for the MyshoppingPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-myshopping',
  templateUrl: 'myshopping.html',
})
export class MyshoppingPage {

  session_data:any;
  token:any;
  lista:any;
  keys:any;
  total:any = 0;
  vacio:any=true;
  id:any;
  tipo:any;
  titulo:any;
  user:any;
  id_pro:any;
  id_vendedor: any;

  constructor(public navCtrl: NavController, private alertCtrl: AlertController, public navParams: NavParams,public _cookieService:CookieService,public api:ApiProvider) {

    this.session_data=JSON.parse(this._cookieService.get('session'));
    this.token=this.session_data.token;
    console.log(this.session_data);

    this.id_vendedor = this.navParams.get('id_vendedor');
    this.id_pro= this.navParams.get('id_pro');
    this.tipo = this.navParams.get('tipo');
    if (this.tipo == 'misproductos') {
      this.titulo = 'Mis Compras Pendientes';
      this.api.getinfo('/api/productoscompras/'+this.session_data.id,{id:this.session_data.id}).then((res: any) => {
        this.lista = res;
        if(this.lista.length > 0){
          this.user = res[0].user.name;
          this.vacio=false
        }
        this.total=this.lista.length;
        this.keys = Object.keys(this.lista);
        console.log(this.lista);
        /* if (this.r.status_code == 200) {
           this.presentToast("Te enviamos un correo, puede tardar unos minutos");
         } else {
           this.presentToast("Ocurrio un error");
         }*/
      });
    }
    if (this.tipo == 'vendedor') {
      this.titulo = 'Productos del vendedor';
      this.api.getinfo('/api/productosusr/'+this.id_vendedor,{id:this.id_vendedor}).then((res: any) => {
        this.lista = res;
        this.user = res[0].user.name;
        if(this.lista.length > 0){
          this.vacio=false
        }
        this.total=this.lista.length;
        this.keys = Object.keys(this.lista);
        console.log(this.lista);
        /* if (this.r.status_code == 200) {
           this.presentToast("Te enviamos un correo, puede tardar unos minutos");
         } else {
           this.presentToast("Ocurrio un error");
         }*/
      });
    }
  }


  godetail(id){
    if(this.tipo == 'vendedor'){
      this.navCtrl.setRoot(DetailreservaPage, {id: id});
    }else {
      let alert = this.alertCtrl.create({
        title: 'Datos del prducto',
        message: 'Especifica la acción, si tu producto fue entregado correctamente, clickea en CONFIRMAR ENTREGA',
        buttons: [
          {
            text: 'Cancel',
            role: 'cancel',
            handler: () => {
              console.log('Cancel clicked');
            }
          },
          {
            text: 'Ver Producto',
            handler: () => {
              this.navCtrl.setRoot(DetailreservaPage, {id: id});
            }
          },
          {
            text: 'Confirmar Entrega',
            handler: () => {
              this.api.postinfo('api/confirmarv', {id: id}).then((res: any) => {
                if (res.status == 'success') {
                  this.api.getinfo('/api/productoscompras/' + this.session_data.id, {id: this.session_data.id}).then((res: any) => {
                    this.lista = res;
                    if (this.lista.length > 0) {
                      this.user = res[0].user.name;
                      this.vacio = false
                    }
                    this.total = this.lista.length;
                    this.keys = Object.keys(this.lista);
                    console.log(this.lista);
                    /* if (this.r.status_code == 200) {
                       this.presentToast("Te enviamos un correo, puede tardar unos minutos");
                     } else {
                       this.presentToast("Ocurrio un error");
                     }*/
                  });
                }
              });
            }
          }
        ]
      });
      alert.present();

    }
  }

  atras() {
    if (this.tipo=='vendedor' ) {
      this.navCtrl.setRoot(DetailreservaPage, {id: this.id_pro});
    }else {
      this.navCtrl.setRoot(PerfilPage);
    }

  }

}

import { Component } from '@angular/core';
import {AlertController, IonicPage, ModalController, NavController, NavParams} from 'ionic-angular';
import {AlertPage} from "../alert/alert";
import {CookieService} from "angular2-cookie/core";
import {ApiProvider} from "../../providers/api/api";
import {EventosPage} from "../eventos/eventos";
import {MyshoppingPage} from "../myshopping/myshopping";
import {PerfilformPage} from "../perfilform/perfilform";

/**
 * Generated class for the DetailreservaPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-detailreserva',
  templateUrl: 'detailreserva.html',
})
export class DetailreservaPage {

  info:any;
  postres:any;
  myParam = {valor:null,messaje:null,fontipe:null,volver:null};
  session_data:any;
  iduser:any;
  idreser:any;
  id:any;
  url:any;
  estado:any;
  imagen:any;
  titulo:any;
  descripcion:any;
  precio:any;
  tipo_moneda:any;
  vendedor:any;
  id_pro:any;
  boton:any;
  vendido:any;
  fotos:any;

  constructor(public alertCtrl: AlertController, public navCtrl: NavController,
              public navParams: NavParams,public _cookieService:CookieService,
              public api:ApiProvider,public modalCtrl: ModalController) {
    this.url = this.api.api_url;
    this.id = this.navParams.get('id');
    if (this.id) {
      this.loadData();
    }

    this.session_data = JSON.parse(this._cookieService.get('session'));
    this.iduser = this.session_data.id;
    console.log(this.iduser);
  }
  ionViewDidLoad() {

  }

  loadData(){
    this.api.getinfo('api/publicaciones/' + this.id,null).then((res: any) => {
      this.info=res;
      this.estado=this.info.estado;
      this.fotos=this.info.publicacion_imagen;

      if(this.info.publicacion_imagen[0]){
        this.imagen= this.url+this.info.publicacion_imagen[0].ruta;
      }else{
        this.imagen = 'assets/nofoto.jpg';
      }
      this.id_pro=this.info.id;
      this.titulo=this.info.titulo;
      this.descripcion=this.info.descripcion;
      this.precio=this.info.precio;
      this.tipo_moneda=this.info.tipo_moneda;
      this.vendedor = this.info.id_user;
      this.boton = this.info.features.disponible;
      this.vendido = this.info.vendido;
      if(this.boton != 'no'){
        this.boton= 'COMPRAR';
      }else{
        this.boton= this.info.features.horario;
      }
      console.log(this.info.image);
    });
  }
  verimg(img){
    this.imagen=img;
  }

  vervendedor() {
    this.navCtrl.setRoot(MyshoppingPage, {id_vendedor: this.vendedor ,tipo:'vendedor', id_pro: this.id_pro});
  }
  godlist() {
    console.log(this.postres);
    this.navCtrl.setRoot(EventosPage);
  }
  reservar(){
    console.log(this.info);
    if(this.info.estado=='activo'){

      let alert = this.alertCtrl.create({
        title: '¿Estas seguro de que quieres comprar este producto?',
        message: 'Tu producto llegara pronto, el pago es contra reembolso, tu dirección es: '+this.session_data.direccion ,

        buttons: [
          {
            text: 'Cancelar',
            handler: (data: any) => {
              console.log('Cancel clicked');
            }
          },
          {
            text: 'Editar Dirección',
            handler: (data: any) => {
              this.navCtrl.setRoot(PerfilformPage);
            }
          },
          {
            text: 'Comprar',
            handler: (data: any) => {

              let body = {
                'id_publicacion':this.id,
                'id_cliente':this.iduser
              }


              this.api.postinfo('admin/comprar', body).then((res: any) => {
                console.log(res);
                if (res.status == 'success') {
                  this.myParam.valor = status;
                  this.myParam.volver = 'eventos';
                  this.myParam.messaje = 'TU RESERVA SE REALIZO.';
                  this.myParam.fontipe = 2;
                  let myModal = this.modalCtrl.create(AlertPage, {'myParam': this.myParam});
                  myModal.present();
                }

              });
            }
          }
        ]
      });
      alert.present();
    }else{
      console.log('selecciona una cabaña');
    }

  }

}

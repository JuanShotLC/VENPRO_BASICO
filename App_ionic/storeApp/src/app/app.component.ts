import { Component, ViewChild } from '@angular/core';
import { Nav, Platform,AlertController } from 'ionic-angular';
import { StatusBar } from '@ionic-native/status-bar';
import { SplashScreen } from '@ionic-native/splash-screen';
import { CookieService } from 'angular2-cookie/core';
import { LoginPage } from '../pages/login/login';
import { LogoutPage } from '../pages/logout/logout';
import { Push,  PushObject, PushOptions } from '@ionic-native/push';
import { Storage } from '@ionic/storage';
import {SliderPage} from "../pages/slider/slider";

@Component({
  templateUrl: 'app.html',
  providers: [CookieService],
})
export class MyApp {
  @ViewChild(Nav) nav: Nav;

  rootPage: any = LoginPage;
  noti:any = true;
  session_data:any;
  idcate:string = null;
  status :string;
  namep:string;

  pages: Array<{title: string, component: any,icon:string}>;

  constructor(private storage:Storage,private alertCtrl: AlertController,public _cookieService:CookieService,public push: Push,public platform: Platform, public statusBar: StatusBar, public splashScreen: SplashScreen) {
   /* this.initializeApp();*/


   if(this._cookieService.get('session')){
      console.log('I have cookies');
      this.session_data = JSON.parse(this._cookieService.get('session'))

    }else{
      console.log('Cookies undefined');
    }

    this.pages = [
      /*{ title: 'Chat Soporte', component: ChatPage, icon:'' },*/
      { title: 'Salir', component: LogoutPage, icon:'log-out' },
    ];

  }

  ngOnInit() {
    if(this._cookieService.get('session')){
      this.storage.get('status').then(status => {
        this.status = status;
        console.log("session activa");

        if (this.status == 'true') {
          this.session_data = JSON.parse(this._cookieService.get('session'));
          this.namep = this.session_data.name;
          this.pushsetup();
          console.log("llego aqui al storage");
        }else{
          this.session_data = null;
        }

      });
    }
  }

  pushsetup(){
    console.log(this.session_data)
    const options: PushOptions = {
       android: {
            senderID: '479364803578',
            topics: [this.session_data.categorie.id],
       },
       ios: {
           alert: 'true',
           badge: true,
           sound: 'false'
       },
       windows: {}
    };

    const pushObject: PushObject = this.push.init(options);

    pushObject.on('notification').subscribe((notification: any) => {
      if(notification.additionalData.foreground){
         console.log('DATA HERE');
        let myalert = this.alertCtrl.create({
          title:notification.title,
          message:notification.message,
        });

        myalert.present();

      }
    });

    if(this.noti==true) {
      pushObject.on('registration').subscribe((registration: any) => {
        this._cookieService.put('token_device', registration.registrationId);
        console.log('Device registered', registration.registrationId);
      });

      pushObject.on('error').subscribe(error => console.error('Error with Push plugin', error));
    }
  }

 /* initializeApp() {
    this.platform.ready().then(() => {
      // Okay, so the platform is ready and our plugins are available.
      // Here you can do any higher level native things you might need.
      /!*--------FCM----------*!/
      console.log(this.nav.getActive().name);
      this.platform.registerBackButtonAction(()=>{
        if(this.nav.getActive().name=='HomePage'){
          let alert = this.alertCtrl.create({
            title: 'Cerrar Sesion',
            message: 'Estas seguro de salir del sistema?',
            buttons: [
              {
                text: 'Cancel',
                role: 'cancel',
                handler: () => {
                  console.log('Cancel clicked');
                }
              },
              {
                text: 'Si',
                handler: () => {
                  this._cookieService.removeAll();
                  this.nav.setRoot(LogoutPage);
                }
              }
            ]
          });
          alert.present();
        }else{
          this.nav.setRoot(SliderPage);
        }
        console.log(this.nav.getActive().name);
        //this.nav.setRoot(HomePage);
      });
      /!*--------END FMC------*!/
      this.statusBar.styleDefault();
      this.splashScreen.hide();
    });
  }*/

  openPage(page) {
    // Reset the content nav to have just this page
    // we wouldn't want the back button to show in this scenario
    this.nav.setRoot(page.component);
  }
}

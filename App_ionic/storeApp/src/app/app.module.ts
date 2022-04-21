import { BrowserModule } from '@angular/platform-browser';
import { ErrorHandler, NgModule } from '@angular/core';
import { IonicApp, IonicErrorHandler, IonicModule } from 'ionic-angular';
import {KSSwiperModule} from 'angular2-swiper/dist/ks-swiper.module';
import { MyApp } from './app.component';
import { LoginPage } from '../pages/login/login';
import { LogoutPage } from '../pages/logout/logout';
import { HttpModule } from "@angular/http";
import { StatusBar } from '@ionic-native/status-bar';
import { SplashScreen } from '@ionic-native/splash-screen';
import { ApiProvider } from '../providers/api/api';
import { CookieService } from 'angular2-cookie/core';
import { FilterForPipe } from '../pipes/filter-for/filter-for';
import { Push } from '@ionic-native/push';
import { FilterdatePipe } from '../pipes/filterdate/filterdate';
import { LimitToPipe } from '../pipes/limit-to/limit-to';
import {IonicStorageModule} from "@ionic/storage";
import {InicioPage} from "../pages/inicio/inicio";
import {InAppBrowser} from "@ionic-native/in-app-browser";
import {RecuperaPage} from "../pages/recupera/recupera";
import {SliderPage} from "../pages/slider/slider";
import {EventosPage} from "../pages/eventos/eventos";
import {DetailPage} from "../pages/detail/detail";
import {AlertPage} from "../pages/alert/alert";
import {CalendarPage} from "../pages/calendar/calendar";
import {Calendar} from "@ionic-native/calendar";
import {RegistoPage} from "../pages/registo/registo";
import {MessajePage} from "../pages/messaje/messaje";
import {PerfilPage} from "../pages/perfil/perfil";
import {PerfilformPage} from "../pages/perfilform/perfilform";
import {MiseventosPage} from "../pages/miseventos/miseventos";
import {MyshoppingPage} from "../pages/myshopping/myshopping";
import {MyservicePage} from "../pages/myservice/myservice";
import {ReservaservicePage} from "../pages/reservaservice/reservaservice";
import {OperationsPage} from "../pages/operations/operations";
import {AlertdeletePage} from "../pages/alertdelete/alertdelete";
import {ReferidoPage} from "../pages/referido/referido";
import {CanjePage} from "../pages/canje/canje";
import {ConfircanjePage} from "../pages/confircanje/confircanje";
import {QrPage} from "../pages/qr/qr";
import {IntroPage} from "../pages/intro/intro";
import {MomentModule} from "angular2-moment";
import { MyDateRangePickerModule } from 'mydaterangepicker';
import {MisserviciosPage} from "../pages/misservicios/misservicios";
import { KeypipePipe } from '../pipes/keypipe/keypipe';
import {EditreservaPage} from "../pages/editreserva/editreserva";
import {NativePageTransitions} from "@ionic-native/native-page-transitions";
import {CabanasPage} from "../pages/cabanas/cabanas";
import {MyreservasPage} from "../pages/myreservas/myreservas";
import {EditreservacionPage} from "../pages/editreservacion/editreservacion";
import {ConfirdeletePage} from "../pages/confirdelete/confirdelete";
import {DetailreservaPage} from "../pages/detailreserva/detailreserva";
import {BarcodeScanner} from "@ionic-native/barcode-scanner";
import { FileTransfer, FileUploadOptions, FileTransferObject } from '@ionic-native/file-transfer';
import {RegisteruserPage} from "../pages/registeruser/registeruser";
import {Geolocation} from "@ionic-native/geolocation";
import {ProductoPage} from "../pages/producto/producto";
import {DROPZONE_CONFIG, DropzoneConfigInterface, DropzoneModule} from "ngx-dropzone-wrapper";
import {PayPal} from "@ionic-native/paypal";
import {MisventasPage} from "../pages/misventas/misventas";
import {FCM} from "@ionic-native/fcm";
import { AppMaskerModule } from 'brmasker-ionic';
import {BrMaskerModule} from "brmasker-ionic-3";
import {EditproPage} from "../pages/editpro/editpro";

const DEFAULT_DROPZONE_CONFIG: DropzoneConfigInterface = {
  // Change this to your upload POST address:
  acceptedFiles: 'image/*',
  createImageThumbnails: true
};
@NgModule({
  declarations: [
    MyApp,
    LoginPage,
    ProductoPage,
    MisventasPage,
    RecuperaPage,
    SliderPage,
    EventosPage,
    DetailPage,
    RegistoPage,
    MiseventosPage,
    ReferidoPage,
    EditproPage,
    MessajePage,
    PerfilPage,
    IntroPage,
    ConfircanjePage,
    ConfirdeletePage,
    DetailreservaPage,
    QrPage,
    CanjePage,
    ReservaservicePage,
    MyreservasPage,
    AlertdeletePage,
    MisserviciosPage,
    OperationsPage,
    MyservicePage,
    RegisteruserPage,
    MyshoppingPage,
    EditreservacionPage,
    CalendarPage,
    PerfilformPage,
    AlertPage,
    InicioPage,
    CabanasPage,
    LogoutPage,
    FilterForPipe,
    EditreservaPage,
    FilterdatePipe,
    LimitToPipe,
    KeypipePipe,
  ],
  imports: [
    BrowserModule,
    MyDateRangePickerModule,
    HttpModule,
    MomentModule,
    KSSwiperModule,
    BrMaskerModule,
    DropzoneModule,
    IonicModule.forRoot(MyApp),
    IonicStorageModule.forRoot()
  ],
  bootstrap: [IonicApp],
  entryComponents: [
    MyApp,
    LoginPage,
    EventosPage,
    RecuperaPage,
    ProductoPage,
    AlertPage,
    RegistoPage,
    CalendarPage,
    MisventasPage,
    MisserviciosPage,
    OperationsPage,
    DetailreservaPage,
    MyshoppingPage,
    EditreservacionPage,
    AlertdeletePage,
    EditreservaPage,
    ConfirdeletePage,
    ReferidoPage,
    IntroPage,
    MyreservasPage,
    RegisteruserPage,
    CanjePage,
    PerfilformPage,
    CabanasPage,
    ReservaservicePage,
    ConfircanjePage,
    QrPage,
    MyservicePage,
    MiseventosPage,
    PerfilPage,
    EditproPage,
    MessajePage,
    DetailPage,
    SliderPage,
    InicioPage,
    LogoutPage,
  ],
  providers: [
    StatusBar,
    {
      provide: DROPZONE_CONFIG,
      useValue: DEFAULT_DROPZONE_CONFIG
    },
    SplashScreen,
    PayPal,
    CookieService,
    Geolocation,
    AlertPage,
    NativePageTransitions,
    Calendar,
    BarcodeScanner,
    FCM,
    CalendarPage,
    InAppBrowser,
    {provide: ErrorHandler, useClass: IonicErrorHandler},
    ApiProvider,
    Push,
    FileTransfer
  ]
})
export class AppModule {}

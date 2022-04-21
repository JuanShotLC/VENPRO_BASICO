import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { MyreservasPage } from './myreservas';

@NgModule({
  declarations: [
    MyreservasPage,
  ],
  imports: [
    IonicPageModule.forChild(MyreservasPage),
  ],
  exports: [
    MyreservasPage
  ]
})
export class MyreservasPageModule {}

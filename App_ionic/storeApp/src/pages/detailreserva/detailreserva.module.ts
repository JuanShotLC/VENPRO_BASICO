import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { DetailreservaPage } from './detailreserva';

@NgModule({
  declarations: [
    DetailreservaPage,
  ],
  imports: [
    IonicPageModule.forChild(DetailreservaPage),
  ],
  exports: [
    DetailreservaPage
  ]
})
export class DetailreservaPageModule {}

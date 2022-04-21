import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { MisserviciosPage } from './misservicios';

@NgModule({
  declarations: [
    MisserviciosPage,
  ],
  imports: [
    IonicPageModule.forChild(MisserviciosPage),
  ],
  exports: [
    MisserviciosPage
  ]
})
export class MisserviciosPageModule {}

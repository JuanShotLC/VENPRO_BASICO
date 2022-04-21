import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { MisventasPage } from './misventas';

@NgModule({
  declarations: [
    MisventasPage,
  ],
  imports: [
    IonicPageModule.forChild(MisventasPage),
  ],
  exports: [
    MisventasPage
  ]
})
export class MisventasPageModule {}

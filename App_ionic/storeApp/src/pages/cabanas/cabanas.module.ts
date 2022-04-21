import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { CabanasPage } from './cabanas';

@NgModule({
  declarations: [
    CabanasPage,
  ],
  imports: [
    IonicPageModule.forChild(CabanasPage),
  ],
  exports: [
    CabanasPage
  ]
})
export class CabanasPageModule {}

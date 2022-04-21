import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { RecuperaPage } from './recupera';

@NgModule({
  declarations: [
    RecuperaPage,
  ],
  imports: [
    IonicPageModule.forChild(RecuperaPage),
  ],
  exports: [
    RecuperaPage
  ]
})
export class RecuperaPageModule {}

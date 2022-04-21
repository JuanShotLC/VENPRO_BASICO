import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { MiseventosPage } from './miseventos';

@NgModule({
  declarations: [
    MiseventosPage,
  ],
  imports: [
    IonicPageModule.forChild(MiseventosPage),
  ],
  exports: [
    MiseventosPage
  ]
})
export class MiseventosPageModule {}

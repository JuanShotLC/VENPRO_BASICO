import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { QrPage } from './qr';

@NgModule({
  declarations: [
    QrPage,
  ],
  imports: [
    IonicPageModule.forChild(QrPage),
  ],
  exports: [
    QrPage
  ]
})
export class QrPageModule {}

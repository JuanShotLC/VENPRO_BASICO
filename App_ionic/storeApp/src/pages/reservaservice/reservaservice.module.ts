import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { ReservaservicePage } from './reservaservice';

@NgModule({
  declarations: [
    ReservaservicePage,
  ],
  imports: [
    IonicPageModule.forChild(ReservaservicePage),
  ],
  exports: [
    ReservaservicePage
  ]
})
export class ReservaservicePageModule {}

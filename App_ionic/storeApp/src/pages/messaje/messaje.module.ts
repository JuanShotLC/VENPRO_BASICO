import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { MessajePage } from './messaje';

@NgModule({
  declarations: [
    MessajePage,
  ],
  imports: [
    IonicPageModule.forChild(MessajePage),
  ],
  exports: [
    MessajePage
  ]
})
export class MessajePageModule {}

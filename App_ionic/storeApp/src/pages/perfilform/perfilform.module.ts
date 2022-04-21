import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { PerfilformPage } from './perfilform';

@NgModule({
  declarations: [
    PerfilformPage,
  ],
  imports: [
    IonicPageModule.forChild(PerfilformPage),
  ],
  exports: [
    PerfilformPage
  ]
})
export class PerfilformPageModule {}

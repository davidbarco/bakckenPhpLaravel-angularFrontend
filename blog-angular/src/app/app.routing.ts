/* imports necesarios */
import{ModuleWithProviders}from '@angular/core';
import{Routes, RouterModule} from "@angular/router";


//importar componenets a los cuales les quiero hacer una pagina exculsiva
import { LoginComponent } from "./components/login/login.component";
import { RegisterComponent } from "./components/register/register.component";
import { HomeComponent } from './components/home/home.component';
import { ErrorComponent } from './components/error/error.component';

//array de rutas
const appRoutes: Routes = [
    /* rutas de la url */
   {path: '', component: HomeComponent},
   {path: 'inicio', component: HomeComponent},
   {path: 'login', component: LoginComponent },
   {path: 'logout/:sure', component: LoginComponent},
   {path: 'registro', component: RegisterComponent },
   
   {path: '**', component: ErrorComponent },
   
];
    
//exportar el modulo de rutas
export const appRoutingProviders: any[] = [];
export const routing: ModuleWithProviders<any> = RouterModule.forRoot(appRoutes);

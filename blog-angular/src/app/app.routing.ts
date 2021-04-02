/* imports necesarios */
import{ModuleWithProviders}from '@angular/core';
import{Routes, RouterModule} from "@angular/router";


//importar componenets a los cuales les quiero hacer una pagina exculsiva
import { LoginComponent } from "./components/login/login.component";
import { RegisterComponent } from "./components/register/register.component";
import { HomeComponent } from './components/home/home.component';
import { ErrorComponent } from './components/error/error.component';
import { UserEditComponent } from './components/user-edit/user-edit.component';
import { CategoryNewComponent } from './components/category-new/category-new.component';
import { PostNewComponent } from './components/post-new/post-new.component';
import { PostDetailComponent } from './components/post-detail/post-detail.component';
import { PostEditComponent } from './components/post-edit/post-edit.component';

//array de rutas
const appRoutes: Routes = [
    /* rutas de la url */
   {path: '', component: HomeComponent},
   {path: 'inicio', component: HomeComponent},
   {path: 'login', component: LoginComponent },
   {path: 'logout/:sure', component: LoginComponent},
   {path: 'registro', component: RegisterComponent },
   {path: 'ajustes', component: UserEditComponent },
   {path: 'crear-categoria', component: CategoryNewComponent },
   {path: 'crear-entrada', component: PostNewComponent },
   {path: 'entrada/:id', component: PostDetailComponent },
   {path: 'editar-entrada/:id', component: PostEditComponent },
   {path: '**', component: ErrorComponent },
   
];
    
//exportar el modulo de rutas
export const appRoutingProviders: any[] = [];
export const routing: ModuleWithProviders<any> = RouterModule.forRoot(appRoutes);

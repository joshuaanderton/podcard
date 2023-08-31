import { Redirect, Route } from "react-router"
import { IonApp, IonRouterOutlet } from "@ionic/react"
import { IonReactRouter } from "@ionic/react-router"
import { KonstaProvider } from "konsta/react"
import Explore from "./pages/Explore"
import "./App.css"

export default () => (
  <KonstaProvider theme="parent">
    <IonApp>
      <IonReactRouter>
        <IonRouterOutlet>
          <Route exact path="/home">
            <Explore />
          </Route>
          <Route exact path="/">
            <Redirect to="/home" />
          </Route>
        </IonRouterOutlet>
      </IonReactRouter>
    </IonApp>
  </KonstaProvider>
)

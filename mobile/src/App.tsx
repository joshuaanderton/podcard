import React from "react"
import { Redirect, Route } from "react-router"
import { IonApp, IonFooter, IonNavLink, IonRouterOutlet } from "@ionic/react"
import { IonReactRouter } from "@ionic/react-router"
import Explore from "./pages/Explore"
import PodcastShow from "./pages/PodcastShow"
import NoneFound from "./pages/NoneFound"
import "./App.css"

const App: React.FC = () => (
  <IonApp>
    <IonReactRouter>
      <IonRouterOutlet>
        <Route exact path="/home" component={Explore} />
        <Route exact path="/" render={() => <Redirect to="/home" />} />
        <Route path="/podcasts/:id" component={PodcastShow} />
        <Route component={NoneFound} />
      </IonRouterOutlet>
    </IonReactRouter>
  </IonApp>
)

export default App

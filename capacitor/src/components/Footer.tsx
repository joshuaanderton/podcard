import React from "react"
import { IonTabBar, IonTabButton, IonIcon, IonLabel } from "@ionic/react"
import { search, heart, home } from 'ionicons/icons'

const Footer: React.FC = () => (
  <IonTabBar slot="bottom" className="pb-5 pt-2">
    <IonTabButton href="/home">
      <IonIcon icon={home} />
      <IonLabel>{'Home'}</IonLabel>
    </IonTabButton>
    <IonTabButton href="/search">
      <IonIcon icon={search} />
      <IonLabel>{'Search'}</IonLabel>
    </IonTabButton>
    <IonTabButton href="/favorites">
      <IonIcon icon={heart} />
      <IonLabel>{'Favorites'}</IonLabel>
    </IonTabButton>
  </IonTabBar>
)
export default Footer

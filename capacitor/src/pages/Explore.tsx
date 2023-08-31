import { IonContent, IonHeader, IonPage, IonToolbar, IonTitle } from "@ionic/react"
import { Block } from 'konsta/react'
import Api from "../utils/Api"
import { useEffect } from "react"

export default () => {

  console.log('load')

  useEffect(() => {
    (new Api).searchPodcasts('ramen').then(resp => console.log('podcasts', resp))
  }, [])

  return (
    <IonPage>
      <IonContent fullscreen>
        <IonHeader collapse="condense">
          <IonToolbar>
            <IonTitle size="large">My App</IonTitle>
          </IonToolbar>
        </IonHeader>
        <Block strong>
          <p>
            Here is your Ionic & Konsta UI app. Let's see what we have here.
          </p>
        </Block>
      </IonContent>
    </IonPage>
  )
}

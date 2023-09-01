import { useState, useEffect } from "react"
import { IonContent, IonHeader, IonPage, IonImg, IonToolbar, IonTitle } from "@ionic/react"
import { Block } from 'konsta/react'
import { Podcast } from "../types"
import Api from "../utils/Api"

export default () => {

  const [podcasts, setPodcasts] = useState<Podcast[]|null>(null)

  useEffect(() => {

    console.log('test')

    ;(new Api).trendingPodcasts().then(resp => setPodcasts(resp ? resp.feeds : null))

  }, [])

  return (
    <IonPage>
      <IonContent fullscreen>
        <IonHeader collapse="condense">
          <IonToolbar>
            <IonTitle size="large">Trending</IonTitle>
          </IonToolbar>
        </IonHeader>
        <Block strong>
          {podcasts?.map(podcast => (
            <Block className="flex items-center space-x-3">
              <IonImg src={podcast.artwork} className="shrink-0 w-10" />
              <span className="font-bold">{podcast.title}</span>
            </Block>
          ))}
          <p>
            Here is your Ionic & Konsta UI app. Let's see what we have here.
          </p>
        </Block>
      </IonContent>
    </IonPage>
  )
}

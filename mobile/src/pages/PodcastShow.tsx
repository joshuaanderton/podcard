import React, { useState, useEffect } from "react"
import { IonContent, IonHeader, IonTitle, IonPage, IonFooter, IonToolbar } from "@ionic/react"
import { Podcast } from "../types"
import PodcastCard from "../components/PodcastCard"
import Footer from "../components/Footer"
import Api from "../utils/Api"

const PodcastShow: React.FC = ({ match }: any) => {

  const [podcast, setPodcast] = useState<Podcast|null>(null)

  useEffect(() => {

    const api = new Api

    api.podcastByFeedId(parseInt(match.params.id)).then(podcast => (
      setPodcast(podcast)
    ))

  }, [])

  return (
    <IonPage>
      <IonHeader>
        <IonToolbar>
          <IonTitle>{podcast ? podcast.title : 'Podcast'}</IonTitle>
        </IonToolbar>
      </IonHeader>
      <IonContent>
        <p>{JSON.stringify(podcast)}</p>
      </IonContent>

      <Footer />

    </IonPage>
  )
}

export default PodcastShow

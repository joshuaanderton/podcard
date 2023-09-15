import React, { useState, useEffect } from "react"
import { IonContent, IonHeader, IonToolbar, IonTitle, IonSearchbar, IonPage } from "@ionic/react"
import { Podcast } from "../types"
import Api from "../utils/Api"
import PodcastCard from "../components/PodcastCard"
import Footer from "../components/Footer"

const Explore: React.FC = () => {

  const [podcasts, setPodcasts] = useState<Podcast[]|null>(null)

  useEffect(() => {

    const api = new Api

    api.trendingPodcasts().then(resp => (
      setPodcasts(resp?.feeds || null)
    ))

  }, [])

  return (
    <IonPage>
      <IonHeader>
        <IonToolbar>
          <IonTitle>{'Explore'}</IonTitle>
        </IonToolbar>
        <IonSearchbar />
      </IonHeader>
      <IonContent>
        <IonHeader className="px-3 pb-3">{'Trending'}</IonHeader>
        <div className="overflow-x-scroll px-1.5 pb-3">
          <div className="flex flex-nowrap">
            {podcasts?.map(podcast => (
              <PodcastCard podcast={podcast} className="shrink-0 w-[36vw]" />
            ))}
          </div>
        </div>
      </IonContent>

      <Footer />

    </IonPage>
  )
}

export default Explore

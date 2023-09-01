import { IonCard, IonImg, IonRouterLink } from "@ionic/react";
import { Podcast } from "../types";
import { Link } from "react-router-dom";

export default ({ podcast, className = '' }: { podcast: Podcast, className?: string }) => (
  <IonCard className={`${className} m-0 rounded-none shadow-none`}>
    <Link to={`/podcasts/${podcast.id}`} className="text-black dark:text-white flex flex-col px-1.5">
      <IonImg src={podcast.artwork} className="shadow-xl" />
      <p className="mt-1 opacity-50 font-normal text-[.7rem] whitespace-nowrap truncate">{Object.values(podcast.categories)[0]}</p>
      <p className="text-xs leading-tight font-semibold truncate">
        {podcast.title}
      </p>
    </Link>
  </IonCard>
)

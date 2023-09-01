import { IonPage, IonTitle } from "@ionic/react";
import { RouteComponentProps } from "react-router";

export default ({ match }: { match: RouteComponentProps }) => (
  <IonPage className="flex">
    <IonTitle className="m-auto">404</IonTitle>
    <p>{JSON.stringify(match)}</p>
  </IonPage>
)

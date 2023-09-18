import React, { useState } from 'react'
import { Head } from '@inertiajs/react'
import TextInput from '@/Components/TextInput'
import PrimaryButton from '@/Components/PrimaryButton'
import axios from 'axios'

interface Props {}

const Artwork: React.FC<Props> = () => {

  const [prompt, setPrompt] = useState<string|null>(null),
        [artwork, setArtwork] = useState<string|null>(null),
        [artworks, setArtworks] = useState<string[]>([]]),
        handleSubmit = (event: any) => {
          event.preventDefault()
          axios.post('/api/generate/artwork', { prompt }).then((response: any) => {
            const newArtwork = response.data.url
            setArtwork(newArtwork)
            setArtworks(artworks => artworks.concat([newArtwork]))
          })
        }

  return (
    <>
      <Head title="Artwork" />
      <div className="h-screen flex items-center justify-center">
        <div className="hidden">{`${artworks.length} artworks created`}</div>
        {artwork ? (
          <div className="max-w-2xl mx-auto p-10 border border-black/20 rounded-2xl">
            <h1 className="text-4xl font-bold">Shazam! Check out your new artwork. Like it?</h1>
            <img src={artwork} className="mt-6" />
            <button onClick={() => setArtwork(null)} type="button" className="mt-4 underline hover:no-underline">Start from scratch</button>
          </div>
        ) : (
          <div className="max-w-2xl mx-auto p-10 border border-black/20 rounded-2xl">
            <h1 className="text-4xl font-bold">What should your podcast's artwork look like?</h1>
            <form onSubmit={handleSubmit}>
              <div className="flex flex-col gap-3 mt-6">
                <TextInput value={prompt || ''} onInput={(event: any) => setPrompt(event.target.value)} placeholder='e.g. "A picture of a racoon talking through a megaphone"' className="flex-1" />
                <PrimaryButton disabled={!(prompt && prompt.length > 0)} className="justify-center !py-3">Generate Artwork</PrimaryButton>
              </div>
            </form>
          </div>
        )}
      </div>
    </>
  )
}

export default Artwork

import React, { useEffect } from 'preact/compat'
import Embed from '@editframe/embed'

new Embed({
  applicationId: 'APP_ID',
  containerId: 'editor-2',
  dimensions: {
    height: '800px',
    width: '100%',
  },
  layers: ['audio', 'image'],
})

export default () => {

  useEffect(() => {

    new Embed({
      applicationId: import.meta.env.VITE_EDITFRAME_CLIENT_ID,
      containerId: 'editor',
      dimensions: {
        height: '800px',
        width: '100%',
      },
      layers: ['audio', 'image'],
    })

  }, [])

  return (
    <div id="editor"></div>
  )
}
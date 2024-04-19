function chatterFetch(url, method, data, contentType='application/json') {
  return new Promise((resolve, reject) => {
    let options = {
      method: method || "GET",
      body: contentType==='application/json' ? JSON.stringify(data) : data,
      mode: "cors",
      headers: {
        "Content-Type": contentType,
        "X-Requested-With": "XMLHttpRequest",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        "X-Chatter-CourseID": window.course_id,
      }
    };
    // if data is a FormData object, browser sets the content type so it can calculate the multipart boundary
    if (contentType==='multipart/form-data') {
      delete options.headers["Content-Type"]
    }
    fetch(url, options).then(response => {
      if (response.ok) {
        resolve(response)
        return
      } else {
        if (response.status === 401) {
          alert('Your login has expired.  Please relaunch Chatter from your course to continue.')
        } else {
          alert('Sorry, but an unexpected error happened.  Please try again.')
        }
      }
      reject(response)
    })
    .catch(error => {
      alert('Sorry, but an unexpected error happened.  Please try again.')
      reject(error)
    })
  })
}

function getCourseId() {
  return window.course_id
}

const chatterApi = {
  async getUserSelf() {
    const response = await chatterFetch('/api/user/self')
    return response.json()
  },
  async getUser(user_id) {
    const response = await chatterFetch('/api/course/' + getCourseId() + '/user/' + user_id)
    return response.json()
  },
  async updateUser(user_id, data) {
    const response = await chatterFetch('/api/course/' + getCourseId() + '/user/' + user_id, 'POST', data)
    return response.json()
  },

  async getCourse() {
    const response = await chatterFetch('/api/course/' + getCourseId())
    return response.json()
  },
  async getCourseSummary() {
    const response = await chatterFetch('/api/course/' + getCourseId() + '/summary')
    return response.json()
  },
  async updateCourse(data) {
    const response = await chatterFetch('/api/course/' + getCourseId(), 'POST', data)
    return response.json()
  },

  async getCoursePosts() {
    const response = await chatterFetch('/api/course/' + getCourseId() + '/posts')
    return response.json()
  },
  async searchCoursePosts(searchParms) {
    const response = await chatterFetch('/api/course/' + getCourseId() + '/posts?' + new URLSearchParams(searchParms))
    return response.json()
  },
  async getPost(post_id) {
    const response = await chatterFetch('/api/course/' + getCourseId() + '/post/' + post_id)
    return response.json()
  },
  async createPost(data) {
    const response = await chatterFetch('/api/course/' + getCourseId() + '/post/new', 'POST', data)
    return response.json()
  },
  async editPost(post_id, data) {
    const response = await chatterFetch('/api/course/' + getCourseId() + '/post/' + post_id, 'POST', data)
    return response.json()
  },
  async editPostTag(post_id, data) {
    const response = await chatterFetch('/api/course/' + getCourseId() + '/post/' + post_id + '/tag', 'POST', data)
    return response.json()
  },
  async lockPost(post_id, locked) {
    const response = await chatterFetch('/api/course/' + getCourseId() + '/post/' + post_id + '/lock/' + locked, 'POST')
    return response.json()
  },
  async pinPost(post_id, pinned) {
    const response = await chatterFetch('/api/course/' + getCourseId() + '/post/' + post_id + '/pin/' + pinned, 'POST')
    return response.json()
  },
  async deletePost(post_id) {
    const response = await chatterFetch('/api/course/' + getCourseId() + '/post/' + post_id, 'DELETE')
    return response
  },

  async createComment(data) {
    const response = await chatterFetch('/api/course/' + getCourseId() + '/comment/new', 'POST', data)
    return response.json()
  },
  async editComment(comment_id, data) {
    const response = await chatterFetch('/api/course/' + getCourseId() + '/comment/' + comment_id, 'POST', data)
    return response.json()
  },
  async endorseComment(comment_id, endorsed) {
    const response = await chatterFetch('/api/course/' + getCourseId() + '/comment/' + comment_id + '/endorse/' + endorsed, 'POST')
    return response.json()
  },
  async muteComment(comment_id, muted) {
    const response = await chatterFetch('/api/course/' + getCourseId() + '/comment/' + comment_id + '/mute/' + muted, 'POST')
    return response.json()
  },
  async upvoteComment(comment_id, upvoted) {
    const response = await chatterFetch('/api/course/' + getCourseId() + '/comment/' + comment_id + '/upvote/' + upvoted, 'POST')
    return response.json()
  },

  async uploadImage(formData){
    const response = await chatterFetch('/api/course/' + getCourseId() + '/upload_file', 'POST', formData, 'multipart/form-data')
    return response.json()
  }
}

export { chatterApi };

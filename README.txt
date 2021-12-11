****************
OpenSong Viewer
Author: J. Klassen
http://mennoknights.com/opensong
****************

*Disclaimer*
THIS SOFTWARE IS PROVIDED "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

*Requirements*
-PHP5 with SimpleXML
-Offline Mode requires the server to recognize the .manifest mime type
-Offline Mode requires write access to the /songs subfolder

*Setup*
-unpack files to a directory on your web server
-upload/FTP song files to the /XML folder under this directory

*Offline Mode*
-go to "[url of your installation]/buildstatic.php" to setup files for offline mode
-this will create static HTML files of all XML song files and a manifest file to control caching of required files
-this must be repeated whenever songs are added or updated